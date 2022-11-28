<?php

namespace App\Jobs;

use App\Models\TransactionHistory;
use App\Models\TransactionRanking;
use App\Models\TransactionRawData;
use App\Services\AuctionInfoService;
use App\Services\RankingService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetRankingJob implements ShouldQueue
{
    use ApiBscScanTransaction, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auctionInfoService;

    protected $rankingService;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->auctionInfoService = new AuctionInfoService();
        $this->rankingService = new RankingService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->getRankingNftAuction();
    }

    public function getRankingNftAuction()
    {
        $results = []; //collect($this->getAllTransactionsBscScan());
        //in case call api success
        if (!empty($results)) {
            // $transactionRawData = collect($this->rankingService->getTransactionRawData());
            // $countTransactionHistory = $this->rankingService->countTransactionHistory();
            // if (!$transactionRawData->isEmpty()) {
            //     //truncate transaction history and ranking
            //     TransactionHistory::truncate();
            //     foreach ($transactionRawData->chunk(50) as $data) {
            //         CreateTransactionJob::dispatch($data)
            //             ->onQueue(config('defines.queue.general'));
            //     }
            // }
            // //truncate raw data table
            // TransactionRawData::truncate();
            // TransactionRanking::truncate();
            // foreach ($results->chunk(50) as $data) {
            //     UpdateRankingJob::dispatch(
            //         $data,
            //         $countTransactionHistory > 0 ? true : false
            //     )->onQueue(config('defines.queue.general'));
            // }
            info("success", [
                'attempts' => $this->attempts(),
                'tries' => $this->tries,
                'backoff' => $this->backoff
            ]);
            $this->release();
        } else {
            info("error", [
                'attempts' => $this->attempts(),
                'tries' => $this->tries,
                'backoff' => $this->backoff
            ]);
        }
    }


    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed()
    {
        info("failed", [
            'attempts' => $this->attempts(),
            'tries' => $this->tries
        ]);
    }
}