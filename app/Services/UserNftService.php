<?php

namespace App\Services;

use App\Models\Nft;

class UserNftService
{
    /**
     * Get nfts of a user
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserNfts($userId)
    {
        $nfts = Nft::where('nft_owner_id', $userId)
                   ->with('nftType')
                   ->get();

        return $nfts;
    }
}
