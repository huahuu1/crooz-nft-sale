<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionHistory extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'nft_auction_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'nft_auction_id',
        'amount',
        'status',
        'tx_hash',
    ];
}
