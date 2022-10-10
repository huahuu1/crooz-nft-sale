<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SaleInfoService;

class InformationController extends Controller
{
    protected $saleInfoService;

    /**
     * InformationController constructor.
     *
     * @param SaleInfoService $saleInfoService
     */
    public function __construct(
        SaleInfoService $saleInfoService
    ) {
        $this->saleInfoService = $saleInfoService;
    }

    /**
     * Display information of the latest token sale follow Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestInfoTokenSale()
    {
        return response()->json([
            'data' => $this->saleInfoService->latestInfoTokenSale(),
        ]);
    }

    /**
     * Display information of token sale by Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfoTokenSaleById($id)
    {
        return response()->json([
            'data' => $this->saleInfoService->infoTokenSaleById($id),
        ]);
    }

    /**
     * Display information of the latest NFT auction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestInfoNftAuction()
    {
        return response()->json([
            'data' => $this->saleInfoService->latestInfoNftAuction(),
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
            'data' => $this->saleInfoService->infoNftAuctionById($id),
        ]);
    }
}
