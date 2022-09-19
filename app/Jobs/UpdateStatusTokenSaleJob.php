<?php

namespace App\Jobs;

use App\Models\TokenSaleHistory;
use Bscscan\APIConf as APIConfBsc;
use Bscscan\Client as ClientBsc;
use Etherscan\APIConf as APIConfEthers;
use Etherscan\Client as ClientEthers;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            Log::info("UpdateStatusTokenSaleJob::" . $this->transaction);
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
            if (!empty($result['transaction_status']['result'])) {
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

                    if (!$transactionStatus) {
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

    /**
     * Check Transaction With Ether Scan
     *
     * @param  mixed  $transaction_hash
     * @return mixed
     */
    public function checkWithApiScan($transaction_hash)
    {
        $api_key = config('defines.api.bsc.api_key') ;
        $apiConfEthers = APIConfEthers::TESTNET_ROPSTEN;
        $apiConfBsc = APIConfBsc::TESTNET;
        // check production or testnet
        if (env('APP_ENV') == 'production') {
            $apiConfEthers = null;
            $apiConfBsc = null;
        }

        switch (config('defines.scan_api')) {
            case 'ETHERS':
                $baseUri = config('defines.api.eth.url');
                $client = new ClientEthers($api_key, $apiConfEthers);
                break;
            case 'BSC':
                $baseUri = config('defines.api.bsc.url');
                $client = new ClientBsc($api_key, $apiConfBsc);
                break;
        }
        Log::info("UpdateStatusTokenSaleJob checkWithApiScan - transaction hash::" . $transaction_hash);
        Log::info("UpdateStatusTokenSaleJob checkWithApiScan - api key::" . $api_key);
        Log::info("UpdateStatusTokenSaleJob checkWithApiScan - apiConfEthers::" . $apiConfEthers);
        Log::info("UpdateStatusTokenSaleJob checkWithApiScan - apiConfBsc:: " . $apiConfBsc);

        //get block of the transaction
        $transactionBlockNumber = $client->api('proxy')->getTransactionByHash($transaction_hash)['result']['blockNumber'];
        //get current block
        $currentBlockNumber = $client->api('proxy')->blockNumber()['result'];

        $blockCount = hexdec($currentBlockNumber) - hexdec($transactionBlockNumber);

        //get transaction status
        $transactionStatus = $client->api('transaction')->getTransactionReceiptStatus($transaction_hash);

        $client = new HttpClient(
            [
                'base_uri' => $baseUri,
                'headers' => [],
            ]
        );
        $params = [
            'query' => [
                'module' => 'proxy',
                'action' => 'eth_getTransactionByHash',
                'txhash' => $transaction_hash,
                'apikey' => $api_key,
            ],
        ];
        $uri = '?';
        $response = $client->request(
            'GET',
            $uri,
            $params
        );
        $responseData = json_decode($response->getBody()->getContents(), true);

        return collect([
            'response' => $responseData,
            'block_count' => $blockCount,
            'transaction_status' => $transactionStatus,
        ]);
    }
}
