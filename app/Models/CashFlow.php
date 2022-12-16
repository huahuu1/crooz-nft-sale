<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CashFlow extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'cash_flows';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'amount',
        'type',
        'transaction_type',
        'tx_hash',
        'payment_method',
        'created_at',
        'updated_at'
    ];

    public const TOKEN_WITHDRAWAL = 1;

    public const TOKEN_DEPOSIT = 2;

    public const NFT_DEPOSIT = 3;

    public const METHOD_CRYPTO = 1;

    public const METHOD_CREDIT = 2;

    public const METHOD_COUPON = 3;
}