<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusTokenSaleJob;
use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;
use GuzzleHttp\Client as GuzzleClient;
use Etherscan\APIConf;
use Etherscan\Client;
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
        $pendingTransactions = $this->transactions->pendingTokenSaleTransactions()->limit(15)->get();
        if (!empty($pendingTransactions)) {
            foreach ($pendingTransactions as $key => $transaction) {
                UpdateStatusTokenSaleJob::dispatch($transaction, $company_wallet, $contract_wallet, $key)->delay(now()->addSeconds(($key + 1) * 3));
            }
        }
    }

    public function checkWithApiScan()
    {

        $api_key = config('defines.api.bsc.api_key');
        $apiConfBsc = APIConf::TESTNET_BSC;
        $baseUri = config('defines.api.bsc.url');

        $client = new GuzzleClient(
            [
                'base_uri' => $baseUri,
                'headers' => [],
            ]
        );
        $params = [
            'query' => [
                'module' => 'proxy',
                'action' => 'eth_getTransactionByHash',
                'txhash' => '0xde2ed71997dd8cd7fedf4b4285906b34578b5c62332ae38fd540e5b34043ab23',
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
        Log::info("check:token-sale responseData::". $response->getBody());
    }
}
