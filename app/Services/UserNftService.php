<?php

namespace App\Services;

use App\Models\Nft;

class UserNftService
{
    /**
     * Get nfts of a user
     *
     * @param $userId
     * @return Nft
     */
    public function getUserNfts($userId)
    {
        $nfts = Nft::where('nft_owner_id', $userId)
                   ->with('nft_type')
                   ->get();

        return $nfts;
    }
}
