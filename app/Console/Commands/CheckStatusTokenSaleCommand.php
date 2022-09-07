<?php

namespace App\Console\Commands;

use App\Models\TokenSaleHistory;
use Etherscan\APIConf as APIConfEthers;
use Etherscan\Client as ClientEthers;
use Bscscan\APIConf as APIConfBsc;
use Bscscan\Client as ClientBsc;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckStatusTokenSaleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:token-sale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Transactions Token Sale Command';

    protected $transactions;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->transactions = new TokenSaleHistory();
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
        $company_wallet = env('COMPANY_WALLET');
        $contract_wallet = env('CONTRACT_WALLET_USDT');

        $pendingTransactions = $this->transactions->pendingTokenSaleTransactions();

        $pendingTransactions->chunkById(100, function ($transactions) use ($company_wallet, $contract_wallet) {
            foreach ($transactions as $transaction) {
                //get transaction information from bscscan
                $result = $this->checkWithApiScan($transaction->tx_hash);
                $response = $result['response'];
                $blockNumberCount = $result['block_count'];
                $transactionStatus = $result['transaction_status']['result']['status'];

                if ($response['result']['blockHash'] == null) {
                    //Update Transaction As Pending
                    $transaction->status = TokenSaleHistory::PENDING_STATUS;
                    $transaction->update();

                    return;
                }

                //validate response
                if ($response && array_key_exists('result', $response)) {
                    $result = $response['result'];
                    //Validate transaction destination with our account
                    if ((strtolower($result['to']) == strtolower($company_wallet)
                        || strtolower($result['to']) == strtolower($contract_wallet))
                        && $blockNumberCount >= env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT')
                        && $transactionStatus
                    ) {
                        //Update Transaction As Success
                        $transaction->status = TokenSaleHistory::SUCCESS_STATUS;
                        $transaction->update();
                    }

                    if (! $transactionStatus) {
                        //Update Transaction As Fail
                        $transaction->status = TokenSaleHistory::FAILED_STATUS;
                        $transaction->update();
                    }
                } else {
                    //Update Transaction As Fail
                    $transaction->status = TokenSaleHistory::FAILED_STATUS;
                    $transaction->update();
                }
                Log::info('[SUCCESS] Check status token sale for: '.$transaction->id.' ('.substr($transaction->tx_hash, 0, 10).')');
                $this->info('[SUCCESS] Check status token sale for: '.$transaction->id.' ('.substr($transaction->tx_hash, 0, 10).')');
            }
        }, 'id');
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

        // check production or testnet
        if (env('APP_ENV') == 'production') {
            switch (env('BLOCKCHAIN_SCAN_API')) {
                case 'ETHERS':
                    $baseUri = 'https://api.etherscan.io/api';
                    $client = new ClientEthers($api_key);
                    break;
                case 'BSC':
                    $baseUri = 'https://api.bscscan.com/api';
                    $client = new ClientBsc($api_key);
                    break;
            }
        } else {
            switch (env('BLOCKCHAIN_SCAN_API')) {
                case 'ETHERS':
                    $baseUri = 'https://api-ropsten.etherscan.io/api';
                    $client = new ClientEthers($api_key, APIConfEthers::TESTNET_ROPSTEN);
                    break;
                case 'BSC':
                    $baseUri = 'https://api-testnet.bscscan.com/api';
                    $client = new ClientBsc($api_key, APIConfBsc::TESTNET);
                    break;
            }
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
