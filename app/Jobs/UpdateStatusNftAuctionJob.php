<?php

namespace App\Jobs;

use App\Models\NftAuctionHistory;
use App\Traits\ApiScanTransaction;
use App\Traits\CheckTransactionWithApiScan;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStatusNftAuctionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiScanTransaction, CheckTransactionWithApiScan;

    protected $transaction;

    protected $company_wallet;

    protected $contract_wallet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $company_wallet, $contract_wallet)
    {
        $this->transaction = $transaction;
        $this->company_wallet = $company_wallet;
        $this->contract_wallet = $contract_wallet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //get transaction information from bscscan
            $result = $this->checkWithApiScan($this->transaction->tx_hash);
            $response = $result['response'];
            $blockNumberCount = $result['block_count'];

            if (! empty($response['error'])) {
                //Update Transaction As Fail
                $this->transaction->status = NftAuctionHistory::FAILED_STATUS;
                $this->transaction->update();
            }

            if (! empty($response) && $response['result']['blockHash'] == null) {
                //Update Transaction As Pending
                $this->transaction->status = NftAuctionHistory::PENDING_STATUS;
                $this->transaction->update();

                return;
            }

            //validate response
            if (! empty($result['transaction_status']['result'])) {
                $transactionStatus = $result['transaction_status']['result']['status'];
                $successBlockCount = $this->configSuccessBlockCount(config('defines.network'));
                if ($response && array_key_exists('result', $response)) {
                    $result = $response['result'];
                    //Validate transaction destination with our account
                    if ((strtolower($result['to']) == strtolower($this->company_wallet)
                            || strtolower($result['to']) == strtolower($this->contract_wallet))
                        && $blockNumberCount >= $successBlockCount
                        && $transactionStatus
                    ) {
                        //Update Transaction As Success
                        $this->transaction->status = NftAuctionHistory::SUCCESS_STATUS;
                        $this->transaction->update();
                    }

                    if (! $transactionStatus) {
                        //Update Transaction As Fail
                        $this->transaction->status = NftAuctionHistory::FAILED_STATUS;
                        $this->transaction->update();
                    }
                }
                Log::info('[SUCCESS] Check Status Nft Auction for: '.$this->transaction->id.' ('.substr($this->transaction->tx_hash, 0, 10).')');
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
