<?php

namespace App\Console\Commands;

use App\Jobs\CreateNftAuctionHistoryJob;
use App\Models\NftAuctionHistory;
use App\Services\AuctionInfoService;
use App\Services\HistoryListService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Console\Command;

class CheckMissTransactionNftAuctionCommand extends Command
{
    use ApiBscScanTransaction;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:miss-nft-auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Miss Transactions NFT Auction Command';

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
        info("Start UpdateMissTransactions");
        $this->UpdateMissTransactions();
        info("End UpdateMissTransactions");
    }

    /**
     * Update Miss Transactions
     *
     * @return void
     */
    public function UpdateMissTransactions()
    {
        // get all auction histories
        $auctions = collect($this->historyListService->getAllNftAuctionHistoriesByPackage(3)->toArray());

        // get all transaction in blockchain
        $dataAuctionHistories = collect($this->getAllTransactionsBscScan());
        if (!empty($dataAuctionHistories)) {
            // new nft auction history data
            $newAuctionHistories = collect([]);

            // get new history by tx_hash
            $dataAuctionHistories->each(function ($item) use ($auctions, $newAuctionHistories) {
                if ($auctions->contains('tx_hash', $item['hash']) === false && $auctions->where('confirmations', '>=', (string)24)) {
                    $newAuctionHistories->push($item);
                }
            });

            // call job insert Nft Auction History
            if (!empty($newAuctionHistories)) {
                foreach ($newAuctionHistories->chunk(20) as  $k => $auctionHistory) {
                    CreateNftAuctionHistoryJob::dispatch(
                        $auctionHistory
                    )
                        ->onQueue(config('defines.queue.general'))
                        ->delay(now()->addSeconds(((int) $k + 1) * 2));
                }
            }
        }
    }
}