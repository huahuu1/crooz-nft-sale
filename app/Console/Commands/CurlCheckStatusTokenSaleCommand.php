<?php

namespace App\Console\Commands;

use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Etherscan\APIConf;
use Etherscan\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class CurlCheckStatusTokenSaleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:curl-token-sale';

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
        $pendingTransactions = $this->transactions->pendingTokenSaleTransactions();
        $pendingTransactions->chunkById(100, function ($transactions) use ($company_wallet) {
            foreach ($transactions as $transaction) {
                //get transaction information from etherscan
                $result = $this->checkWithEtherScan($transaction->tx_hash);
                // $response = $result->get('response');
                // $blockNumberCount = $result->get('block_count');
                // $transactionStatus = $result->get('transaction_status')['status'];


                // if ($response['result']['blockHash'] == null) {
                //     //Update Transaction As Pending
                //     $transaction->status = TokenSaleHistory::PENDING_STATUS;
                //     $transaction->update();
                //     return;
                // }

                // //validate response
                // if ($response && array_key_exists('result', $response)) {
                //     $result = $response['result'];
                //     //Validate transaction destination with our account
                //     if (strtolower($result['to']) == strtolower($company_wallet) && $blockNumberCount >= env('SUCCESS_TRANSACTION_BLOCK_COUNT') && $transactionStatus) {
                //         //Update Transaction As Success
                //         $transaction->status = TokenSaleHistory::SUCCESS_STATUS;
                //         $transaction->update();
                //     }
                // } else {
                //     //Update Transaction As Fail
                //     $transaction->status = TokenSaleHistory::FAILED_STATUS;
                //     $transaction->update();
                // }
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
        $api_key = 'ENBK5HBW1JFGY2INMUN28UDA88VM1Y6GJS'; // api from from Etherscan.io
        $test_network = "https://api-ropsten.etherscan.io"; //use in testnet
        $main_network = "https://etherscan.io"; //use in mainnet

        //use lib maslakoff/php-etherscan-api
        // $client = new Client($api_key, APIConf::TESTNET_ROPSTEN);

        // //get block of the transaction
        // $transactionBlockNumber = $client->api('proxy')->getTransactionByHash($transaction_hash)['result']['blockNumber'];
        // //get current block
        // $currentBlockNumber = $client->api('proxy')->blockNumber()['result'];

        // $blockCount = hexdec($currentBlockNumber) - hexdec($transactionBlockNumber);

        // //get transaction status
        // $transactionStatus = $client->api('transaction')->getTransactionReceiptStatus($transaction_hash);

        // $response = Http::acceptJson()->get(
        //     $test_network
        //         . "/api/?module=proxy&action=eth_getTransactionByHash&txhash="
        //         . $transaction_hash
        //         . '&apikey=' . $api_key
        // );

        // $response->header(json_encode(['User-Agent' => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/50.0.2661.102 Safari/537.36"]));
        $url = $test_network . "/api/?module=proxy&action=eth_getTransactionByHash&txhash=" . $transaction_hash . '&apikey=' . $api_key;
        info($url);
        $getGuzzleHtml = $this->getGuzzleHtml($url);
        info($getGuzzleHtml);
        $getUrlContent = $this->getUrlContent($url);
        info($getUrlContent);

        // return collect([
        //     'response' => $response->json(),
        //     'block_count' => $blockCount,
        //     'transaction_status' => $transactionStatus
        // ]);
    }

    /**
     * Get Html by GuzzleHttp\Client
     *
     * @param [string] $url
     * @return json
     */
    public function getGuzzleHtml($url)
    {
        $client = new GuzzleClient();
        $json = $client->request('GET', $url)->getBody();
        return $json;
    }

    /**
     * get website content with url by curl
     */
    public function getUrlContent($url): String
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
