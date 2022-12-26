<?php

namespace App\Services;

use App\Models\NftAuctionWeaponGachaId;
use App\Models\NftDeliverySource;
use App\Models\XenoClassSaleTime;
use App\Traits\ApiGachaTicket;

class GachaService
{
    use ApiGachaTicket;

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

    /**
     * Call api gacha NFT
     *
     * @param $packageId, $auctionId, $purchaseTime, $walletAddress
     * @return array|object
     */
    public function callApiGachaNft($packageId, $auctionId, $purchaseTime, $walletAddress, $auctionNftService, $nftService)
    {
        $baseUri = config('defines.gacha_api_url');
        $xenoGacha = $this->getXenoGachaId($packageId, $purchaseTime, $auctionId);
        // get xeno gacha id
        if (!$xenoGacha) {
            info("[FAIL_GachaNFT] Xeno Gacha Id not found");
            info("[FAIL_GachaNFT] Wallet address: " . $walletAddress);
            info("[FAIL_GachaNFT] Purchase time: " . $purchaseTime);
            return response()->json([
                'message' => "Xeno Gacha Id not found"
            ], 400);
        } else {
            // call api to get gacha NFT
            $nftXenoId = $this->gachaTicket($baseUri, $walletAddress, $xenoGacha->xeno_gacha_id)['response']['result'][0];
            info("XENO Gacha ID: " . $xenoGacha->xeno_gacha_id);
            info("XENO NFT ID: " . $nftXenoId);
            // get weapon gacha id
            $weaponGachaId = NftAuctionWeaponGachaId::getNftAuctionWeaponGachaIdsByNftId($nftXenoId)->weapon_gacha_id;
            info("Weapon Gacha ID: " . $weaponGachaId);
            // call api to get gacha NFT
            $nftWeaponId = $this->gachaTicket($baseUri, $walletAddress, $weaponGachaId)['response']['result'][0];
            info("Weapon NFT ID: " . $nftWeaponId);
            // get nft delivery id
            $deliveryId = NftDeliverySource::getDeliverySourceIdByPackageId($packageId)->nft_delivery_id;
            // save to auction nft
            $auctionNftService->createNftAuctions(
                $walletAddress,
                array($nftXenoId, $nftWeaponId),
                $deliveryId,
                1
            );
            // get all nft of gaCha result
            return $nftService->getNftByIds(array($nftXenoId, $nftWeaponId)) ?? [];
        }
    }
}
