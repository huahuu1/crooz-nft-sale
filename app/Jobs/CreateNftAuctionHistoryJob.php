<?php

namespace App\Jobs;

use App\Models\NftAuctionHistory;
use App\Services\HistoryListService;
use App\Services\UserService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNftAuctionHistoryJob implements ShouldQueue
{
    use ApiBscScanTransaction, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * New Histories variable
     *
     * @var array
     */
    protected $newHistories;

    /**
     * History List Service variable
     *
     * @var App\Services\HistoryListService
     */
    protected $historyListService;

    /**
     * User Service variable
     *
     * @var App\Services\UserService
     */
    protected $userService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newHistories)
    {
        $this->newHistories = $newHistories;
        $this->userService = new UserService();
        $this->historyListService = new HistoryListService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->newHistories as $val) {
            // get user by from address
            $user = $this->userService->hasUserByWalletAddress($val['from']);

            // get token id
            $tokenId = $val['tokenSymbol'] === 'BSC-USD' ? 5 : 7;
            $auctionId = 3;
            $amount = $this->convertAmount($val['tokenDecimal'], $val['value']);

            if ($amount > 0) {
                // create nft Auction History
                $this->historyListService->createNftAuctionHistoryByData(
                    $val['hash'],
                    $user->id,
                    $tokenId,
                    $auctionId,
                    $amount,
                );
                info("[SUCCESS] Create nft auction History: " . $val['hash']);
            }
        }
    }
}