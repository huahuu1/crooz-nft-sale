<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusTokenSaleJob;
use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;
use GuzzleHttp\Client as GuzzleClient;
use Etherscan\APIConf;
use Etherscan\Client;
use GuzzleHttp\Psr7\Request;
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
        $company_wallet = config('defines.wallet.company_token_sale');
        $contract_wallet = config('defines.wallet.usdt');
        $this->checkWithApiScan();
        // run 15 row in 1 min
        // $pendingTransactions = $this->transactions->pendingTokenSaleTransactions()->limit(15)->get();
        // if (!empty($pendingTransactions)) {
        //     foreach ($pendingTransactions as $key => $transaction) {
        //         UpdateStatusTokenSaleJob::dispatch($transaction, $company_wallet, $contract_wallet, $key)->delay(now()->addSeconds(($key + 1) * 3));
        //     }
        // }
    }

    public function checkWithApiScan()
    {
        try {
            //code...
            $api_key = config('defines.api.bsc.api_key');
            $baseUri = config('defines.api.bsc.url');

            $client = new GuzzleClient();
            $params = [
                'query' => [
                    'module' => 'proxy',
                    'action' => 'eth_getTransactionByHash',
                    'txhash' => '0xde2ed71997dd8cd7fedf4b4285906b34578b5c62332ae38fd540e5b34043ab23',
                    'apikey' => $api_key,
                ],
            ];
            $response = new Request(
                'GET',
                'https://api-testnet.bscscan.com/api?module=proxy&action=eth_getTransactionByHash&txhash=0xde2ed71997dd8cd7fedf4b4285906b34578b5c62332ae38fd540e5b34043ab23&apikey=G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM',
            );
            $res = $client->sendAsync($response)->wait();
            Log::info("check:token-sale responseData::" . $res->getBody());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::error("response" . $response);
            Log::error("responseBodyAsString" . $responseBodyAsString);
        }
    }
}
