<?php

namespace App\Jobs;

use App\Models\NftAuctionHistory;
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

class UpdateStatusNftAuctionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $transactionStatus = $result['transaction_status']['result']['status'];

            if ($response['result']['blockHash'] == null) {
                //Update Transaction As Pending
                $this->transaction->status = NftAuctionHistory::PENDING_STATUS;
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

    /**
     * Check Transaction With Ether Scan
     *
     * @param  mixed  $transaction_hash
     * @return mixed
     */
    public function checkWithApiScan($transaction_hash)
    {
        $api_key = env('BSCSCAN_API_KEY');
        $apiConfEthers = APIConf::TESTNET_ROPSTEN;
        $apiConfBsc = APIConf::TESTNET_BSC;
        // check production or testnet
        if (env('APP_ENV') == 'production') {
            $apiConfEthers = null;
            $apiConfBsc = APIConf::NET_BSC;
        }

        switch (env('BLOCKCHAIN_SCAN_API')) {
            case 'ETHERS':
                $baseUri = env('ETHERSSCAN_API_URL');
                $client = new Client($api_key, $apiConfEthers);
                break;
            case 'BSC':
                $baseUri = env('BSCSCAN_API_URL');
                $client = new Client($api_key, $apiConfBsc);
                break;
        }
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
