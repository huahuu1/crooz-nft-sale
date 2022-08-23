<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionInfo extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'nft_auction_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'min_price',
        'status',
    ];

    /**
     * Display information of the latest nft auction follow Id.
     *
     * @return \Illuminate\Http\Response
     */
    public static function getLatestInfoNftAuction()
    {
        return NftAuctionInfo::orderby('id', 'desc')->first();
    }
}
