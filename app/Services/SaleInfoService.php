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
    public function getSaleInfo($id)
    {
        return TokenSaleInfo::select('rule_id', 'price', 'end_date')->where('id', $id)->first();
    }
}
