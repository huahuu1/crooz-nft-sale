<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class EarningWallet extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'earning_wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'lock_time_id',
        'amount',
        'type',
        'status',
        'interest',
        'dividend',
        'tx_hash',
    ];
}
