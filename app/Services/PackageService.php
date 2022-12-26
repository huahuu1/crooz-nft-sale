<?php

namespace App\Services;

use App\Models\NftAuctionPackage;

class PackageService
{
    /**
     * get nft auction package by address and auction id
     *
     * @param array $address, $auctionId
     * @return NftAuctionPackage::class
     */
    public function getNftAuctionPackageByAddress($address, $auctionId)
    {
        return NftAuctionPackage::select('id', 'auction_id', 'price', 'unit_price', 'destination_address')
            ->where('destination_address', $address)
            ->where('auction_id', $auctionId)
            ->first();
    }
}
