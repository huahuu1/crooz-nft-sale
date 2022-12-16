<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRankingJob;
use App\Models\TransactionHistory;
use App\Notifications\EmailFailedJobNotification;
use App\Services\AuctionInfoService;
use App\Services\RankingService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;
use Log;
use Notification;

class UpdateRankingCommand extends Command
{
    use ApiBscScanTransaction;
    use Notifiable;

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

    protected $rankingService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->auctionInfoService = new AuctionInfoService();
        $this->rankingService = new RankingService();
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
        $results = collect($this->getAllTransactionsBscScan('transaction', 3));
        if ($results->isEmpty()) {
            Log::error("Failed to call api bsc scan");
            $email = config('defines.mail_receive_failed_job');
            Notification::route('mail', $email)->notify(new EmailFailedJobNotification($email));
            return;
        }
        //in case call api success
        if (!$results->isEmpty()) {
            $transactionRawData = collect($this->rankingService->getTransactionRawData());
            $countTransactionHistory = $this->rankingService->countTransactionHistory();
            if (!$transactionRawData->isEmpty()) {
                //truncate transaction history and ranking
                TransactionHistory::truncate();
                // insert all transaction history
                TransactionHistory::insert($transactionRawData->toArray());
            }

            // update ranking
            UpdateRankingJob::dispatch(
                $results,
                $countTransactionHistory > 0 ? true : false
            )->onQueue(config('defines.queue.general'));
        }
    }
}
