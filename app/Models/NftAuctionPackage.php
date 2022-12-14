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
}