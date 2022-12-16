<?php

namespace App\Jobs;

use App\Models\CashFlow;
use App\Services\AuctionInfoService;
use App\Services\CashFlowService;
use App\Services\HistoryListService;
use App\Services\UserService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Bus\Queueable;
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
     * Auction Id variable
     *
     * @var int
     */
    protected $auctionId;

    /**
     * History List Service variable
     *
     * @var App\Services\HistoryListService
     */
    protected $historyListService;

    /**
     * auction Info Service variable
     *
     * @var App\Services\AuctionInfoService
     */
    protected $auctionInfoService;

    /**
     * User Service variable
     *
     * @var App\Services\UserService
     */
    protected $userService;

     /**
     * CashFlow Service variable
     *
     * @var App\Services\CashFlowService
     */
    protected $cashFlowService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($newHistories, $auctionId)
    {
        $this->newHistories = $newHistories;
        $this->auctionId = $auctionId;
        $this->userService = new UserService();
        $this->historyListService = new HistoryListService();
        $this->auctionInfoService = new AuctionInfoService();
        $this->cashFlowService = new CashFlowService();
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
            $tokens = $this->dataConfig($val['contractAddress']);
            $tokenId = $tokens['token'] === 'BUSD' ? 5 : 7;
            $amount = $this->convertAmount($val['tokenDecimal'], $val['value']);

            if ($amount > 0) {
                // create nft Auction History
                $this->historyListService->createNftAuctionHistoryByData(
                    $val['hash'],
                    $user->id,
                    $tokenId,
                    $this->auctionId,
                    $amount,
                    date('Y-m-d H:i:s', $val['timeStamp'])
                );
                // create cashflow
                $this->cashFlowService->createCashFlowWithDate(
                    $user->id,
                    $tokenId,
                    $amount,
                    CashFlow::TOKEN_DEPOSIT,
                    $val['hash'],
                    CashFlow::METHOD_CRYPTO,
                    date('Y-m-d H:i:s', $val['timeStamp'])
                );
                // call api to get gacha NFT

                info("[SUCCESS] Create nft auction History: " . $val['hash']);
            }
        }
    }
}