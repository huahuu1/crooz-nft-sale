<?php

namespace App\Jobs;

use App\Models\CashFlow;
use App\Models\NftAuctionHistory;
use App\Models\NftAuctionPackageStock;
use App\Models\UserCouponHold;
use App\Services\AuctionInfoService;
use App\Services\AuctionNftService;
use App\Services\CashFlowService;
use App\Services\GachaService;
use App\Services\HistoryListService;
use App\Services\NftService;
use App\Services\PackageService;
use App\Services\UserCouponService;
use App\Services\UserService;
use App\Traits\ApiBscScanTransaction;
use App\Traits\ApiGachaTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNftAuctionHistoryJob implements ShouldQueue
{
    use ApiBscScanTransaction;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ApiGachaTicket;

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
     * Package Service variable
     *
     * @var App\Services\PackageService
     */
    protected $packageService;

    /**
     * Auction NftService variable
     *
     * @var App\Services\AuctionNftService
     */
    protected $auctionNftService;

    /**
     * Gacha Service variable
     *
     * @var App\Services\GachaService
     */
    protected $gachaService;

    /**
     * Nft Service variable
     *
     * @var App\Services\NftService
     */
    protected $nftService;

    /**
     * User Coupon Service variable
     *
     * @var App\Services\UserCouponService
     */
    protected $userCouponService;

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
        $this->packageService = new PackageService();
        $this->auctionNftService = new AuctionNftService();
        $this->gachaService = new GachaService();
        $this->nftService = new NftService();
        $this->userCouponService = new UserCouponService();
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
            // get package id
            $package = $this->packageService->getNftAuctionPackageByAddress($val['to'], $this->auctionId);
            // in case amount > 0 and amount must equal package's price
            if ($amount > 0 && $amount == $package->price) {
                if (!$package) {
                    info("[FAIL] Package Id not found: " . $val['hash']);
                } else {
                    $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($package->id);
                    //prevent out of stock package
                    if (!empty($packageStock) && $packageStock->remain <= 0) {
                        info("[FAIL] Package out of stock: " . $val['hash']);
                    } else {
                        if ($package->id == 12) {
                            // get user coupon
                            $userCoupon = $this->userCouponService->hasUserCoupon($user->id, $this->auctionId);
                            //calculate amount after discount
                            $amount = $amount * $userCoupon->discount_percentage / 100;
                        }
                        // create nft Auction History
                        $this->historyListService->createNftAuctionHistoryByData(
                            $val['hash'],
                            $user->id,
                            $tokenId,
                            $this->auctionId,
                            $amount,
                            $package->id,
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
                        //subtract ticket when transaction is success
                        if (!empty($packageStock)) {
                            $packageStock->remain -= 1;
                            $packageStock->update();
                        }
                        if ($this->auctionId == 5) {
                            if ($package->id == 12) {
                                // get user coupon hold
                                $couponHold = $this->userCouponService->getUserCouponHold($userCoupon->id, $package->id);
                                // delete user coupon hold
                                UserCouponHold::where('id', $couponHold->id)->delete();
                                // create user coupon history
                                $this->userCouponService->createUserCouponHistoryByDate($userCoupon->id, $couponHold->purchased_time);
                                // create data in nft auction history
                                $this->historyListService->createNftAuctionHistory(
                                    $user->id,
                                    $tokenId,
                                    $this->auctionId,
                                    $amount,
                                    NftAuctionHistory::SUCCESS_STATUS,
                                    null,
                                    NftAuctionHistory::METHOD_COUPON,
                                    $package->id
                                );
                                // insert record in cash flow
                                $this->cashFlowService->createCashFlow(
                                    $user->id,
                                    $tokenId,
                                    $amount,
                                    CashFlow::NFT_DEPOSIT,
                                    null,
                                    CashFlow::METHOD_COUPON
                                );
                            }
                            // get NFT id by nft_auction_rewards
                            $nfts = $this->gachaService->getNftByPackageId($package->id);
                            $this->auctionNftService->createNftAuction(
                                $val['from'],
                                $nfts->nft->nft_id,
                                $nfts->delivery->id,
                                1
                            );
                        } else {
                            // call api gacha NFT
                            $this->gachaService->callApiGachaNfts($package->id, $this->auctionId, date('Y-m-d H:i:s', $val['timeStamp']), $val['from'], $this->auctionNftService, $this->nftService);
                        }

                        info("[SUCCESS] Create nft auction History: " . $val['hash']);
                    }
                }
            } else {
                info("[FAIL] Invalid package's price: " . $val['hash']);
            }
        }
    }
}
