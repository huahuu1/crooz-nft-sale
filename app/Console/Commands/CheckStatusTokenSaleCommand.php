<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusTokenSaleJob;
use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;

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
            foreach ($transactions as $key => $transaction) {
                UpdateStatusTokenSaleJob::dispatch($transaction, $company_wallet, $contract_wallet, $key)->delay(now()->addSeconds(($key + 1) * 3));
            }
        }, 'id');
    }
}
