<?php

namespace App\Jobs;

use App\Models\CashFlow;
use App\Models\NftAuctionPackageStock;
use App\Models\NftAuctionWeaponGachaId;
use App\Models\NftDeliverySource;
use App\Services\AuctionInfoService;
use App\Services\AuctionNftService;
use App\Services\CashFlowService;
use App\Services\GachaService;
use App\Services\HistoryListService;
use App\Services\NftService;
use App\Services\PackageService;
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
                // get package id
                $package = $this->packageService->getNftAuctionPackageByAddress($val['to']);
                if (!$package) {
                    info("[FAIL] Package Id not found: " . $val['hash']);
                } else {
                    $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($package->id);
                    //prevent out of stock package
                    if (!empty($packageStock) && $packageStock->remain <= 0) {
                        info("[FAIL] Package out of stock: " . $val['hash']);
                    } else {
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
                        // call api gacha NFT
                        $this->gachaService->callApiGachaNft($package->id, $this->auctionId, date('Y-m-d H:i:s', $val['timeStamp']), $val['from'], $this->auctionNftService, $this->nftService);

                        info("[SUCCESS] Create nft auction History: " . $val['hash']);
                    }
                }
            }
        }
    }
}
