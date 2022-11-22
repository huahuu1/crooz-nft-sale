<?php

namespace App\Services;

use App\Models\Nft;

class NftService
{
    /**
     * get nfts by where in nft ids
     *
     * @param array $NftIds
     * @return Nft::class
     */
    public function getNftByIds($NftIds)
    {
        return Nft::select('nft_id', 'name', 'image_url')->whereIn('nft_id', $NftIds)
            ->get();
    }
}
