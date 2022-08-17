<?php

namespace App\Console\Commands;

use App\Models\NftAuctionHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Etherscan\APIConf;
use Etherscan\Client;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class CheckStatusNftAuctionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:nft-auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Transactions NFT Auction Command';

    protected $transactions;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->transactions = new NftAuctionHistory();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->validateTransactions();
    }

    /**
     * Validate Metamask Transaction
     *
     * @return void
     */
    public function validateTransactions()
    {
        $company_wallet = env('NFT_COMPANY_WALLET');
        $contract_wallet = env('CONTRACT_WALLET');

        $pendingTransactions = $this->transactions->pendingNftAuctionTransactions();

        $pendingTransactions->chunkById(100, function ($transactions) use ($company_wallet, $contract_wallet) {
            foreach ($transactions as $transaction) {
                //get transaction information from etherscan
                $result = $this->checkWithEtherScan($transaction->tx_hash);
                $response = $result['response'];
                $blockNumberCount = $result['block_count'];
                $transactionStatus = $result['transaction_status']['status'];

                if ($response['result']['blockHash'] == null) {
                    //Update Transaction As Pending
                    $transaction->status = NftAuctionHistory::PENDING_STATUS;
                    $transaction->update();
                    return;
                }

                //validate response
                if ($response && array_key_exists('result', $response)) {
                    $result = $response['result'];
                    //Validate transaction destination with our account
                    if (strtolower($result['to']) == strtolower($company_wallet) || strtolower($result['to']) == strtolower($contract_wallet) && $blockNumberCount >= env('SUCCESS_TRANSACTION_BLOCK_COUNT') && $transactionStatus) {
                        //Update Transaction As Success
                        $transaction->status = NftAuctionHistory::SUCCESS_STATUS;
                        $transaction->update();
                    }
                } else {
                    //Update Transaction As Fail
                    $transaction->status = NftAuctionHistory::FAILED_STATUS;
                    $transaction->update();
                }
                Log::info('[SUCCESS] Check Status Nft Auction for: ' . $transaction->id . ' (' . substr($transaction->tx_hash, 0, 10) . ')');
                $this->info('[SUCCESS] Check Status Nft Auction for: ' . $transaction->id . ' (' . substr($transaction->tx_hash, 0, 10) . ')');
            }
        }, 'id');
    }

    /**
     * Check Transaction With Ether Scan
     *
     * @param  mixed $transaction_hash
     * @return mixed
     */
    public function checkWithEtherScan($transaction_hash)
    {
        $api_key = env('ETHERSCAN_API_KEY'); // api from from Etherscan.io

        // check production or testnet
        if (env('APP_ENV') == 'production') {
            $baseUri = 'https://etherscan.io/api';
            $client = new Client($api_key);
        } else {
            $baseUri = 'https://api-ropsten.etherscan.io/api';
            $client = new Client($api_key, APIConf::TESTNET_ROPSTEN);
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
                'headers'  => []
            ]
        );
        $params = [
            'query' => [
                'module' => 'proxy',
                'action' => 'eth_getTransactionByHash',
                'txhash' => $transaction_hash,
                'apikey' => $api_key,
            ]
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
            'transaction_status' => $transactionStatus
        ]);;
    }
}
