<?php

namespace App\Services;

use App\Models\NftAuctionPackage;

class PackageService
{
    /**
     * get nft auction package by address
     *
     * @param array $address
     * @return NftAuctionPackage::class
     */
    public function getNftAuctionPackageByAddress($address)
    {
        return NftAuctionPackage::select('id', 'auction_id', 'price', 'unit_price', 'destination_address')
            ->where('destination_address', $address)
            ->first();
    }
}
