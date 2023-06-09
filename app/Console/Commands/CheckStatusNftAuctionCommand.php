<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusNftAuctionJob;
use App\Models\NftAuctionHistory;
use App\Traits\CheckTransactionWithApiScan;
use Illuminate\Console\Command;

class CheckStatusNftAuctionCommand extends Command
{
    use CheckTransactionWithApiScan;

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
     * @see validateTransactions
     * @return bool call validateTransactions
     */
    public function handle()
    {
        return $this->validateTransactions();
    }

    /**
     * Validate Metamask Transaction
     */
    public function validateTransactions()
    {
        $company_wallet = config('defines.wallet.company_nft');
        // run 10 row in 1 min
        $pendingTransactions = $this->transactions->pendingNftAuctionTransactions()->limit(10)->get();
        if (! empty($pendingTransactions)) {
            foreach ($pendingTransactions as $key => $transaction) {
                UpdateStatusNftAuctionJob::dispatch(
                    $transaction,
                    $company_wallet
                )
                    ->onQueue(config('defines.queue.check_status'))
                    ->delay(now()->addSeconds(($key + 1) * 5));
            }
        }
    }
}
