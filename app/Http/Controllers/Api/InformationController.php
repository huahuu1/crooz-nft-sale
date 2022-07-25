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
    public function getInfoTokenSaleById($id)
    {
        return response()->json([
            'data' => TokenSaleInfo::find($id),
        ]);
    }
}
