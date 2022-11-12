<?php

namespace App\Services;

use App\Models\AuctionNft;

class AuctionNftService
{
    /**
     * Create NFT Auction
     *
     * @param string $walletAddress
     * @param int $nftId
     * @param int $nftDeliveryId
     * @param bool $status
     * @return App\Models\AuctionNft|array
     */
    public function createNftAuction($walletAddress, $nftId, $nftDeliveryId, $status)
    {
        return AuctionNft::create([
            'wallet_address' => $walletAddress,
            'nft_id' => $nftId,
            'nft_delivery_source_id' => $nftDeliveryId,
            'status' => $status,
        ]);
    }
}
