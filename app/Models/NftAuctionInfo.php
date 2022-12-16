<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionInfo extends Model
{
    use HasApiTokens;
    use HasFactory;

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
        'name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = [
        'packages:id,auction_id,price,unit_price,destination_address',
        'xenoClassSaleTime:id,auction_id,xeno_class_id,start_time,end_time'
    ];

    /**
     * Display information of the latest nft auction follow Id.
     *
     * @return \App\Models\NftAuctionInfo
     */
    public static function getLatestInfoNftAuction()
    {
        return NftAuctionInfo::select(
            'id',
            'start_date',
            'end_date',
            'min_price',
            'status',
            'name',
        )
            ->orderby('id', 'desc')
            ->first();
    }

    /**
     * Get the network info relate to auction nft.
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function auctionNetwork(): HasManyThrough
    {
        return $this->hasManyThrough(NetworkMaster::class, AuctionNetwork::class, 'auction_id', 'id', 'id', 'network_id');
    }
    /**
     * Get all Packages into auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Packages(): HasMany
    {
        return $this->hasMany(NftAuctionPackage::class, 'auction_id', 'id');
    }

    /**
     * Get the xenoClassSaleTime that owns the NftAuctionInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function xenoClassSaleTime(): HasMany
    {
        return $this->hasMany(XenoClassSaleTime::class, 'auction_id', 'id');
    }
}
