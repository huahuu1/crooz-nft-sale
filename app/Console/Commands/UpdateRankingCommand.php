<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRankingJob;
use App\Jobs\UpdateStatusNftAuctionJob;
use App\Models\NftAuctionHistory;
use App\Services\AuctionInfoService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Console\Command;

class UpdateRankingCommand extends Command
{
    use ApiBscScanTransaction;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ranking nft auction Command';

    protected $auctionInfoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auctionInfoService = new AuctionInfoService();
    }

    /**
     * Execute the console command.
     * @see updateRankingNftAuction
     * @return bool call updateRankingNftAuction
     */
    public function handle()
    {
        return $this->updateRankingNftAuction();
    }

    /**
     * Update ranking nft auction
     */
    public function updateRankingNftAuction()
    {
        $results = $this->getAllTransactionsBscScan();
        foreach ($results as $result) {
            //in case call api success
            if (!empty($result)) {
                UpdateRankingJob::dispatch(
                    $result
                )->onQueue(config('defines.queue.general'));
            }
        }
    }
}
