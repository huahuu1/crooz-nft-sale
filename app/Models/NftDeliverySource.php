<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class NftDeliverySource extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nft_delivery_sources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];


    /**
     * Get the nft delivery id by package id.
     *
     * @return \App\Models\NftAuctionReward
     */
    public static function getDeliverySourceIdByPackageId($packageId)
    {
        return NftAuctionReward::select('nft_delivery_id')
            ->where('package_id', $packageId)
            ->first();
    }
}
