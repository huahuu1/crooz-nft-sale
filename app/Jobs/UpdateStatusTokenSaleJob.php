<?php

namespace App\Jobs;

use App\Models\TokenSaleHistory;
use App\Traits\ApiScanTransaction;
use Etherscan\APIConf;
use Etherscan\Client;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateStatusTokenSaleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ApiScanTransaction;

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
            $transactionStatus = $result['transaction_status']['result']['status'];

            if ($response['result']['blockHash'] == null) {
                //Update Transaction As Pending
                $this->transaction->status = TokenSaleHistory::PENDING_STATUS;
                $this->transaction->update();

                return;
            }

            //validate response
            if (! empty($result['transaction_status']['result'])) {
                if ($response && array_key_exists('result', $response)) {
                    $result = $response['result'];
                    //Validate transaction destination with our account
                    if ((strtolower($result['to']) == strtolower($this->company_wallet)
                        || strtolower($result['to']) == strtolower($this->contract_wallet))
                        && $blockNumberCount >= env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT')
                        && $transactionStatus
                    ) {
                        //Update Transaction As Success
                        $this->transaction->status = TokenSaleHistory::SUCCESS_STATUS;
                        $this->transaction->update();
                        //update lock amount balance of user
                        CreateOrUpdateUserBalanceJob::dispatch($this->transaction)->delay(now()->addSeconds(($this->key + 1) * 3));
                    }

                    if (! $transactionStatus) {
                        //Update Transaction As Fail
                        $this->transaction->status = TokenSaleHistory::FAILED_STATUS;
                        $this->transaction->update();
                    }
                }
            }
            Log::info('[SUCCESS] Check status token sale for: '.$this->transaction->id.' ('.substr($this->transaction->tx_hash, 0, 10).')');
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Check Transaction With Ether Scan
     *
     * @param  mixed  $transaction_hash
     * @return mixed
     */
    public function checkWithApiScan($transaction_hash)
    {
        $api_key = env('BSCSCAN_API_KEY');

        switch (env('BLOCKCHAIN_SCAN_API')) {
            case 'ETHERS':
                $baseUri = env('ETHERSSCAN_API_URL');
                break;
            case 'BSC':
                $baseUri = env('BSCSCAN_API_URL');
                break;
        }
        //get block of the transaction
        $transactionBlockNumber = $this->getTransactionByHash($transaction_hash, $baseUri, $api_key)['result']['blockNumber'];
        //get current block
        $currentBlockNumber = $this->getBlockNumber($baseUri, $api_key)['result'];

        $blockCount = hexdec($currentBlockNumber) - hexdec($transactionBlockNumber);

        //get transaction status
        $transactionStatus = $this->getTransactionReceiptStatus($transaction_hash, $baseUri, $api_key);

        $responseData = $this->getTransactionByHash($transaction_hash, $baseUri, $api_key);
        return collect([
            'response' => $responseData,
            'block_count' => $blockCount,
            'transaction_status' => $transactionStatus,
        ]);
    }
}
