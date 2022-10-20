<?php

namespace App\Services;

use App\Models\AuctionNft;
use App\Models\Nft;
use App\Models\NftType;

class UserNftService
{
    /**
     * Get nfts of a user
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNfts($userId, $maxPerPage)
    {
        return AuctionNft::where('owner_id', $userId)
                  ->with('nftType')
                  ->get()
                  ->paginate($maxPerPage);
    }

    /**
     * Get nfts of a user by type id
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNftsByTypeId($walletAddress, $nftType, $maxPerPage)
    {
        return AuctionNft::select(
            'auction_nfts.id',
            'auction_nfts.wallet_address',
            'auction_nfts.nft_id',
            'auction_nfts.nft_auction_id',
            'nfts.nft_type',
            'nfts.name',
            'nfts.image_url',
            'auction_nfts.status'
        )
            ->join('nfts', 'nfts.nft_id', '=', 'auction_nfts.nft_id')
            ->where('wallet_address', $walletAddress)
            ->where('nfts.nft_type', $nftType)
            ->get()
            ->paginate($maxPerPage);
    }

    /**
     * Count nfts group by type id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countNftGroupByTypeId($walletAddress)
    {
        return NftType::select(['id', 'name','status'])
        ->withCount(['auctionNfts' => function ($query) use ($walletAddress) {
            $query->where('wallet_address', $walletAddress);
        }])
        ->get();
    }
}
