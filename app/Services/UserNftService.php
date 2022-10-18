<?php

namespace App\Services;

use App\Models\Nft;
use DB;

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
        return Nft::where('nft_owner_id', $userId)
                   ->with('nftType')
                   ->get()
                   ->paginate($maxPerPage);
    }

    /**
     * Get nfts of a user by type id
     *
     * @param $userId
     * @return Nft
     */
    public function getUserNftsByTypeId($userId, $typeId, $maxPerPage)
    {
        return Nft::where('nft_owner_id', $userId)
                   ->where('type_id', $typeId)
                   ->with('nftType')
                   ->get()
                   ->paginate($maxPerPage);
    }

    /**
     * Count nfts group by type id
     *
     * @return Nft
     */
    public function countNftGroupByTypeId($userId)
    {
        return Nft::select('type_id', DB::raw('count(*) as total'))->where('nft_owner_id', $userId)->with(['nftType:id,name'])->groupBy('type_id')->get();
    }
}
