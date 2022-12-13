<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionPackage extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'nft_auction_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auction_id',
        'price',
        'unit_price',
        'destination_address',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = [
        'Reward',
        'PackageStock',
        'NftClass:sale_date,package_id,xeno_class,auction_id,xeno_gacha_id,weapon_gacha_id'
    ];

    /**
     * Get Reward into Package Auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Reward(): BelongsTo
    {
        return $this->belongsTo(NftAuctionReward::class, 'id', 'package_id');
    }

    /**
     * Get Reward into Package Stock Auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PackageStock(): BelongsTo
    {
        return $this->belongsTo(NftAuctionPackageStock::class, 'id', 'package_id');
    }

    /**
     * Get all of the nft class for the NftAuctionPackage by today
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function NftClass(): BelongsTo
    {
        $startSaleDate = Carbon::today('UTC')->format('Y-m-d') . " 20:00:00";
        $endSaleDate = Carbon::today('UTC')->addDays(1)->format('Y-m-d') . " 19:59:59";

        return $this->belongsTo(NftClass::class, 'id', 'package_id')->whereBetween('sale_date', [$startSaleDate, $endSaleDate]);
    }
}