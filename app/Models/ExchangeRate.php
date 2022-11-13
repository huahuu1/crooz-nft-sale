<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ExchangeRate extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'exchange_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'symbol',
        'rate',
        'status',
        'rate_timestamp',
    ];

    /**
     * Get exchange rate by symbol.
     *
     * @return \App\Models\ExchangeRate
     */
    public static function getExchangeRateBySymbol($symbol)
    {
        return ExchangeRate::select(
            'symbol',
            'rate',
        )
            ->where('symbol', $symbol)
            ->where('status', 1)
            ->get();
    }

    /**
     * Get exchange rate.
     *
     * @return \App\Models\ExchangeRate
     */
    public static function getLastExchangeRate()
    {
        return ExchangeRate::select(
            'symbol',
            'rate',
        )
            ->where('status', 1)
            ->latest('created_at')
            ->first();
    }
}
