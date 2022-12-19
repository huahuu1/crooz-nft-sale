<?php

namespace App\Services;

use App\Models\XenoClassSaleTime;

class GachaService
{
    /**
     * Get xeno gacha id.
     *
     * @param $packageId, $purchaseTime
     * @return array|object
     */
    public function getXenoGachaId($packageId, $purchaseTime, $auctionId)
    {
        return XenoClassSaleTime::select('nft_auction_xeno_gacha_ids.xeno_gacha_id')
            ->join('nft_auction_xeno_gacha_ids', 'xeno_class_sale_times.id', '=', 'nft_auction_xeno_gacha_ids.sale_time_id')
            ->where('nft_auction_xeno_gacha_ids.package_id', $packageId)
            ->where('xeno_class_sale_times.auction_id', $auctionId)
            ->where('xeno_class_sale_times.start_time', '<=', $purchaseTime)
            ->where('xeno_class_sale_times.end_time', '>=', $purchaseTime)
            ->first();
    }
}
