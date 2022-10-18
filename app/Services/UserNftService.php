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
    public function getUserNftsByTypeId($userId, $typeId, $maxPerPage)
    {
        return AuctionNft::where('owner_id', $userId)
                  ->where('type_id', $typeId)
                  ->with('nftType')
                  ->get()
                  ->paginate($maxPerPage);
    }

    /**
     * Count nfts group by type id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function countNftGroupByTypeId($userId)
    {
        return NftType::select(['id', 'name','status'])->withCount(['nfts' => function ($query) use ($userId) {
            $query->where('owner_id', $userId);
        }])->get();
    }
}
