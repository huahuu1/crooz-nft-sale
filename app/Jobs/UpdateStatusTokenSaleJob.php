<?php

namespace App\Jobs;

use App\Models\TokenSaleHistory;
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

class UpdateStatusTokenSaleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ApiScanTransaction;
    use CheckTransactionWithApiScan;

    protected $transaction;

    protected $company_wallet;

    protected $contract_wallet;

    protected $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $company_wallet, $contract_wallet, $key)
    {
        $this->transaction = $transaction;
        $this->company_wallet = $company_wallet;
        $this->contract_wallet = $contract_wallet;
        $this->key = $key;
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
            //checking time of pending transaction
            $timeCheckingStatus = Carbon::now()->diffInHours(TokenSaleHistory::select('created_at')->where('tx_hash', $this->transaction->tx_hash)->first()->created_at);

            if (!empty($response['error']) || $timeCheckingStatus >= 6) {
                //Update Transaction As Fail
                $this->transaction->status = TokenSaleHistory::FAILED_STATUS;
                $this->transaction->update();
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
                            || strtolower($result['to']) == strtolower($this->contract_wallet))
                        && $blockNumberCount >= $successBlockCount
                        && $transactionStatus
                    ) {
                        //Update Transaction As Success
                        $this->transaction->status = TokenSaleHistory::SUCCESS_STATUS;
                        $this->transaction->update();
                        //update lock amount balance of user
                        CreateOrUpdateUserBalanceJob::dispatch($this->transaction)->onQueue(config('defines.queue.check_status'))->delay(now()->addSeconds(($this->key + 1) * 3));
                    }

                    if (!$transactionStatus) {
                        Log::info("UpdateStatusTokenSaleJob - FAILED", ['result' => $result, 'transactionStatus' => $transactionStatus]);
                        //Update Transaction As Fail
                        $this->transaction->status = TokenSaleHistory::FAILED_STATUS;
                        $this->transaction->update();
                    }
                }
            }
            Log::info('[SUCCESS] Check status token sale for: ' . $this->transaction->id . ' (' . substr($this->transaction->tx_hash, 0, 10) . ')');
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
