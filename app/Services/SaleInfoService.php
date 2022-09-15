<?php

namespace App\Services;

use App\Models\TokenSaleInfo;

class SaleInfoService
{
    /**
     * Get sale info by Id
     *
     * @param id
     * @return tokenSaleInfo
     */
    public function getSaleInfoAndUnlockRule($id)
    {
        $tokenSaleInfo = TokenSaleInfo::select('rule_id', 'price', 'end_date')->where('id', $id)->first();
        $tokenSaleInfo['token_unlock_rules'] = $tokenSaleInfo->token_unlock_rules;

        return $tokenSaleInfo;
    }
}
