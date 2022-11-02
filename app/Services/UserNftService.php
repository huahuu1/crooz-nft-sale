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
     * Get all auction nfts
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAuctionNfts($params, $maxPerPage)
    {
        return AuctionNft::select(
            'id',
            'wallet_address',
            'nft_id',
            'nft_delivery_source_id',
            'status',
            'created_at as upload_date'
        )
        ->with('nfts:nft_id,nft_type,name,image_url,status')
        ->orderBy('upload_date', 'DESC')
        ->when(!empty($params['wallet_address']), function ($q) use ($params) {
            $keyword = '%' . $params['wallet_address'] . '%';
            $q->where('wallet_address', 'like', $keyword);
        })
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
            'auction_nfts.nft_delivery_source_id',
            'nfts.nft_type',
            'nfts.name',
            'nfts.image_url',
            'auction_nfts.status'
        )
            ->join('nfts', 'nfts.nft_id', '=', 'auction_nfts.nft_id')
            ->where('wallet_address', $walletAddress)
            ->where('nfts.nft_type', $nftType)
            ->where('auction_nfts.status', '=', 1)
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
