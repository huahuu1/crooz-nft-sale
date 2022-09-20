<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusTokenSaleJob;
use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;
use GuzzleHttp\Client as Client;
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
        // run 15 row in 1 min
        $pendingTransactions = $this->transactions->pendingTokenSaleTransactions()->limit(15)->get();
       $this->getDataDemo();
        if (! empty($pendingTransactions)) {
            foreach ($pendingTransactions as $key => $transaction) {
                UpdateStatusTokenSaleJob::dispatch($transaction, $company_wallet, $contract_wallet, $key)->delay(now()->addSeconds(($key + 1) * 3));
            }
        }
    }

    public function getDataDemo()
    {

        $client = new Client(
            [
                'base_uri' => 'https://api-testnet.bscscan.com/api',
                'headers' => []
            ]
        );
        $params = [
            'query' => [
                'module' => 'proxy',
                'action' => 'eth_getTransactionByHash',
                'txhash' => '0xde2ed71997dd8cd7fedf4b4285906b34578b5c62332ae38fd540e5b34043ab23',
                'apikey' => 'G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM',
            ],
        ];
        $uri = '?';
        $response = $client->request(
            'GET',
            $uri,
            $params
        );
        Log::info("CheckStatusTokenSaleCommand-getDataDemo::" . $response->getBody());
    }

}
