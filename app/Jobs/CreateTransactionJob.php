<?php

namespace App\Jobs;

use App\Services\RankingService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTransactionJob implements ShouldQueue
{
    use ApiBscScanTransaction;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * New Histories variable
     *
     * @var array
     */
    protected $newHistories;

    /**
     * Ranking Service variable
     *
     * @var App\Services\RankingService
     */
    protected $rankingService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newHistories)
    {
        $this->newHistories = $newHistories;
        $this->rankingService = new RankingService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->newHistories as $val) {
            // create transaction history
            $this->rankingService->createTransactionHistory(
                $val->chain,
                $val->tx_hash,
                $val->from,
                $val->to,
                $val->token,
                $val->value,
                $val->created_at
            );
        }
    }
}
