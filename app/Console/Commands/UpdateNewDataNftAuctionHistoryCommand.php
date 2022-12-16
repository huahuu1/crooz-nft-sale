<?php

namespace App\Console\Commands;

use App\Jobs\CreateNftAuctionHistoryJob;
use App\Models\NftAuctionHistory;
use App\Services\AuctionInfoService;
use App\Services\HistoryListService;
use App\Traits\ApiBscScanTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateNewDataNftAuctionHistoryCommand extends Command
{
    use ApiBscScanTransaction;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:nft-auction-history {auction_id=4}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update new data to NFT Auction History Command';

    /**
     *  The transactions of NftAuctionHistory
     *
     * @var App\Services\HistoryListService
     */
    protected $historyListService;

    /**
     * The auction Info Service
     *
     * @var App\Services\AuctionInfoService
     */
    protected $auctionInfoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HistoryListService $historyListService)
    {
        parent::__construct();
        $this->historyListService = $historyListService;
        $this->auctionInfoService = new AuctionInfoService();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        info("Start UpdateNftAuctionHistory");
        $this->updateNftAuctionHistory();
        info("End UpdateNftAuctionHistory");
    }

    /**
     * Update Miss Transactions
     *
     * @return void
     */
    public function updateNftAuctionHistory()
    {
        // get all auction histories
        $auctions = collect($this->historyListService->getAllNftAuctionHistoriesByPackage($this->argument('auction_id'))->toArray());
        // get all transaction in blockchain
        $dataAuctionHistories = collect($this->getAllTransactionsBscScan('transaction', $this->argument('auction_id')));
        $auctionInfo = $this->auctionInfoService->infoNftAuctionById($this->argument('auction_id'));
        if (!empty($dataAuctionHistories)) {
            $startDate = Carbon::parse($auctionInfo->start_date, 'UTC')->getTimestamp();
            $endDate = Carbon::parse($auctionInfo->end_date, 'UTC')->getTimestamp();
            // new nft auction history data
            $newAuctionHistories = collect([]);
            // get new history by tx_hash
            $dataAuctionHistories->each(function ($item) use ($auctions, $newAuctionHistories, $startDate, $endDate) {
                if (
                    $auctions->contains('tx_hash', $item['hash']) === false &&
                    $item['confirmations'] >= 24 &&
                    $item['timeStamp'] >= $startDate &&
                    $item['timeStamp'] <= $endDate
                ) {
                    $newAuctionHistories->push($item);
                }
            });

            // call job insert Nft Auction History
            if (!empty($newAuctionHistories)) {
                foreach ($newAuctionHistories->chunk(20) as $k => $auctionHistory) {
                    CreateNftAuctionHistoryJob::dispatch(
                        $auctionHistory,
                        $this->argument('auction_id')
                    )
                        ->onQueue(config('defines.queue.general'))
                        ->delay(now()->addSeconds(((int) $k + 1) * 2));
                }
            }
        }
    }
}
