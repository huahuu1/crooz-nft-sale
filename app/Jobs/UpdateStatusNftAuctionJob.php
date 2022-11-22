<?php

namespace App\Jobs;

use App\Models\NftAuctionHistory;
use App\Models\NftAuctionPackageStock;
use App\Services\HistoryListService;
use App\Traits\ApiScanTransaction;
use App\Traits\CheckTransactionWithApiScan;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStatusNftAuctionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ApiScanTransaction;
    use CheckTransactionWithApiScan;

    protected $transaction;

    protected $company_wallet;

    protected $historyListService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $company_wallet)
    {
        $this->transaction = $transaction;
        $this->company_wallet = $company_wallet;
        $this->historyListService = new HistoryListService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $network = $this->historyListService->getNftAuctionHistoryByTxHash($this->transaction->tx_hash)->networkMaster;
            $token = $this->historyListService->getNftAuctionHistoryByTxHash($this->transaction->tx_hash)->tokenMaster;
            //get transaction information from bscscan
            $result = $this->checkWithApiScan($this->transaction->tx_hash, $network->chain_id);
            $response = $result['response'];
            $blockNumberCount = $result['block_count'];
            //checking time of pending transaction
            $timeCheckingStatus = Carbon::now()
                ->diffInHours(NftAuctionHistory::select('created_at')
                    ->where(
                        'tx_hash',
                        $this->transaction->tx_hash
                    )
                    ->first()->created_at);

            if (!empty($response['error']) && $timeCheckingStatus >= 6) {
                //Update Transaction As Fail
                $this->updateStatusTransaction($this->transaction, NftAuctionHistory::FAILED_STATUS);
                //refund ticket when transaction is fail
                $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($this->transaction->package_id);
                if (!empty($packageStock)) {
                    $packageStock->remain += 1;
                    $packageStock->update();
                }
            }
            //validate response
            if (!empty($result['transaction_status']['result'])) {
                $transactionStatus = $result['transaction_status']['result']['status'] ?? null;
                $successBlockCount = $this->configSuccessBlockCount(config('defines.network'));
                if ($response && array_key_exists('result', $response) && !empty($response['result'])) {
                    $result = $response['result'];
                    //Validate transaction destination with our account
                    if (
                        (strtolower($result['to']) == strtolower($this->company_wallet)
                            || strtolower($result['to']) == strtolower($token->contract_wallet))
                        && $blockNumberCount >= $successBlockCount
                        && $transactionStatus
                    ) {
                        //Update Transaction As Success
                        $this->updateStatusTransaction($this->transaction, NftAuctionHistory::SUCCESS_STATUS);
                        // Call Job Distribute Ticket
                        DistributeTicketJob::dispatch($this->transaction)->onQueue(config('defines.queue.general'));
                    }
                    //transaction is failed if failed three times
                    if ((!$transactionStatus || is_null($transactionStatus))&& $this->transaction->number_of_failed >= 3) {
                        Log::info(
                            "UpdateStatusNftAuctionJob - FAILED",
                            [
                                'result' => $result,
                                'transactionStatus' => $transactionStatus
                            ]
                        );
                        //Update Transaction As Fail
                        $this->updateStatusTransaction($this->transaction, NftAuctionHistory::FAILED_STATUS);
                        //refund ticket when transaction is fail
                        $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($this->transaction->package_id);
                        if (!empty($packageStock)) {
                            $packageStock->remain += 1;
                            $packageStock->update();
                        }
                    }
                    //in case transaction failed < 3 times
                    if (!$transactionStatus) {
                        //update number of runs when fail
                        $this->transaction->number_of_failed += 1;
                        //Update Transaction As Fail
                        $this->updateStatusTransaction($this->transaction, NftAuctionHistory::PENDING_STATUS);
                    }
                }
                Log::info(
                    '[SUCCESS] Check Status Nft Auction for: '
                        . $this->transaction->id . ' ('
                        . substr($this->transaction->tx_hash, 0, 10)
                        . ')'
                );
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Update status transaction.
     *
     * @return void
     */
    public function updateStatusTransaction($transaction, $status)
    {
        try {
            $transaction->status = $status;
            $transaction->update();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
