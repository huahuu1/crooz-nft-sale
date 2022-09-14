<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusNftAuctionJob;
use App\Models\NftAuctionHistory;
use Illuminate\Console\Command;

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
        $contract_wallet = env('CONTRACT_WALLET_USDT');

        $pendingTransactions = $this->transactions->pendingNftAuctionTransactions();

        $pendingTransactions->chunkById(100, function ($transactions) use ($company_wallet, $contract_wallet) {
            foreach ($transactions as $key => $transaction) {
                UpdateStatusNftAuctionJob::dispatch($transaction, $company_wallet, $contract_wallet)->delay(now()->addSeconds(($key + 1) * 3));
            }
        }, 'id');
    }
}
