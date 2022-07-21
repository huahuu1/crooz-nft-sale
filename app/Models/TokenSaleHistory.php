<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenSaleHistory extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'token_sale_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'token_sale_id',
        'amount',
        'status',
        'tx_hash',
    ];
}
