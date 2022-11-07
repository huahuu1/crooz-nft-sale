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
            'status'
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
        return NftAuctionInfo::select('id', 'start_date', 'end_date', 'min_price', 'status')->find($id);
    }
}
