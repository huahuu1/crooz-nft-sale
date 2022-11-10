<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\TokenMaster;
use App\Services\AuctionInfoService;

class InformationController extends Controller
{
    protected $auctionInfoService;

    /**
     * InformationController constructor.
     *
     * @param AuctionInfoService $auctionInfoService
     */
    public function __construct(
        AuctionInfoService $auctionInfoService
    ) {
        $this->auctionInfoService = $auctionInfoService;
    }

    /**
     * Display information of the latest NFT auction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestInfoNftAuction()
    {
        return response()->json([
            'data' => $this->auctionInfoService->latestInfoNftAuction(),
        ]);
    }

    /**
     * Display information of NFT auction by Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoNftAuctionById($id)
    {
        return response()->json([
            'data' => $this->auctionInfoService->infoNftAuctionById($id),
        ]);
    }

    /**
     * Display all information of NFT auction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllInfoNftAuction()
    {
        return response()->json([
            'data' => $this->auctionInfoService->infoAllNftAuction(),
        ]);
    }

    /**
     * Get token master info.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTokenMasterInfo()
    {
        return response()->json([
            'data' => TokenMaster::getTokenMastersWithNetwork(),
        ]);
    }

    /**
     * Get exchange rate info.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExchangeRateBySymbol($symbol)
    {
        return response()->json([
            'data' => ExchangeRate::getExchangeRateBySymbol($symbol),
        ]);
    }
}
