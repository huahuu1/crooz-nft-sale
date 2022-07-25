<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TokenSaleInfo;

class InformationController extends Controller
{
    /**
     * Display information of token sale.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfoTokenSale()
    {
        return response()->json([
            'data' => TokenSaleInfo::all(),
        ]);
    }
}
