<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NftAuctionInfo;
use App\Models\TokenSaleInfo;

class InformationController extends Controller
{
    /**
     * Display information of the latest token sale follow Id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestInfoTokenSale()
    {
        return response()->json([
            'data' => TokenSaleInfo::orderby('id', 'desc')->first(),
        ]);
    }

    /**
     * Display information of token sale by Id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfoTokenSaleById($id)
    {
        return response()->json([
            'data' => TokenSaleInfo::find($id),
        ]);
    }

    /**
     * Display information of the latest NFT auction follow Id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLatestInfoNftAuction()
    {
        return response()->json([
            'data' => NftAuctionInfo::getLatestInfoNftAuction(),
        ]);
    }

    /**
     * Display information of NFT auction by Id.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfoNftAuctionById($id)
    {
        return response()->json([
            'data' => NftAuctionInfo::find($id),
        ]);
    }
}
