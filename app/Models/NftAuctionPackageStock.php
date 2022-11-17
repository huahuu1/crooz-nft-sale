<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionPackageStock extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nft_auction_package_stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id',
        'total',
        'remain'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * Display information of the package stock by package id.
     *
     * @return \App\Models\NftAuctionPackageStock
     */
    public static function getPackageStockByPackageId($packageId)
    {
        return NftAuctionPackageStock::select()
        ->where('package_id', $packageId)
        ->lockForUpdate()
        ->first();
    }
}
