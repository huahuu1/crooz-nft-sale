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
        $auction = NftAuctionInfo::select(
            'id',
            'start_date',
            'end_date',
            'min_price',
            'status',
            'name',
        )
            ->with(
                [
                    'auctionNetwork:network_masters.id,network_masters.chain_id,network_masters.rpc_urls,network_masters.block_explorer_urls,network_masters.chain_name,network_masters.unit',
                ]
            )
            ->orderby('id', 'desc')
            ->first();

        return $auction;
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
            'name'
        )
            ->with(
                [
                    'auctionNetwork:network_masters.id,network_masters.chain_id,network_masters.rpc_urls,network_masters.block_explorer_urls,network_masters.chain_name,network_masters.unit',
                ]
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
        )
            ->with(
                [
                    'auctionNetwork:network_masters.id,network_masters.chain_id,network_masters.rpc_urls,network_masters.block_explorer_urls,network_masters.chain_name,network_masters.unit',
                ]
            )
            ->where('status', 1)
            ->get();
    }
}
