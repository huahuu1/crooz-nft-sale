<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ExchangeRateService
{
    /**
     * Create exchange rate data
     *
     * @param $symbol, $rate
     */
    public function createExchangeRate($symbol, $rate, $rateTimestamp)
    {
        DB::beginTransaction();
        try {
            DB::table('exchange_rates')
                ->where('symbol', '=', $symbol)
                ->where('status', '=', 1)
                ->update(['status' => 0]);

            DB::table('exchange_rates')->insert([
                'symbol' => $symbol,
                'rate' => $rate,
                'rate_timestamp' => $rateTimestamp,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
