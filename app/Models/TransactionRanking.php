<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TransactionRanking extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'auction_rankings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_address',
        'amount'
    ];
}
