<?php

namespace App\Console\Commands;

use App\Jobs\DistributeTicketJob;
use App\Models\NftAuctionHistory;
use Illuminate\Console\Command;

class distributeTicketUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'distribute:ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute ticket to users';

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
        $pendingTransactions = $this->transactions->pendingNftAuctionCreditTransactions()->get();
        if (! empty($pendingTransactions)) {
            foreach ($pendingTransactions as $transaction) {
                DistributeTicketJob::dispatch($transaction,)
                ->onQueue(config('defines.queue.distribute_ticket'));
            }
        }
    }
}
