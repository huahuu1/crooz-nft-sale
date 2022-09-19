<?php

namespace App\Services;

use App\Models\NftAuctionInfo;
use App\Models\TokenSaleInfo;

class SaleInfoService
{
    /**
     * Get sale info by Id
     *
     * @param $id
     * @return tokenSaleInfo
     */
    public function getSaleInfoAndUnlockRule($id)
    {
        $tokenSaleInfo = TokenSaleInfo::select('rule_id', 'price', 'end_date')->where('id', $id)->first();
        $tokenSaleInfo['token_unlock_rules'] = $tokenSaleInfo->token_unlock_rules;

        return $tokenSaleInfo;
    }

    /**
     * get information of the latest NFT auction.
     *
     * @return NftAuctionInfo
     */
    public function latestInfoNftAuction()
    {
        return NftAuctionInfo::select('id', 'start_date', 'end_date', 'min_price', 'status')->orderby('id', 'desc')->first();
    }

    /**
     * get information of the latest token sale.
     *
     * @return TokenSaleInfo
     */
    public function latestInfoTokenSale()
    {
        return TokenSaleInfo::select('id', 'start_date', 'end_date', 'total', 'total_supply', 'price', 'status')->orderby('id', 'desc')->first();
    }

    /**
     * get information of token sale by id.
     *
     * @param $id
     * @return TokenSaleInfo
     */
    public function infoTokenSaleById($id)
    {
        return TokenSaleInfo::select('id', 'start_date', 'end_date', 'total', 'total_supply', 'price', 'status')->find($id);
    }

    /**
     * get information of NFT auction by id.
     *
     * @param $id
     * @return NftAuctionInfo
     */
    public function infoNftAuctionById($id)
    {
        return NftAuctionInfo::select('id', 'start_date', 'end_date', 'min_price', 'status')->find($id);
    }
}
