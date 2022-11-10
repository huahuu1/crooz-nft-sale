<?php

namespace App\Services;

use App\Models\NftAuctionInfo;

class AuctionInfoService
{
    /**
     * get information of the latest NFT auction.
     *
     * @return NftAuctionInfo
     */
    public function latestInfoNftAuction()
    {
        return NftAuctionInfo::select(
            'id',
            'start_date',
            'end_date',
            'min_price',
            'status',
            'name',
            'fixed_price'
        )
        ->orderby('id', 'desc')
        ->first();
    }

    /**
     * get information of NFT auction by id.
     *
     * @param $id
     * @return NftAuctionInfo
     */
    public function infoNftAuctionById($id)
    {
        return NftAuctionInfo::select(
            'id',
            'start_date',
            'end_date',
            'min_price',
            'status',
            'name',
            'fixed_price'
        )
        ->find($id);
    }

    /**
     * get all information of NFT auction.
     *
     * @param $id
     * @return NftAuctionInfo
     */
    public function infoAllNftAuction()
    {
        return NftAuctionInfo::select(
            'id',
            'start_date',
            'end_date',
            'min_price',
            'status',
            'name',
            'fixed_price'
        )
        ->where('status', 1)
        ->get();
    }
}
