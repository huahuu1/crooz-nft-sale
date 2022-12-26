<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class UserCoupon extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'user_coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nft_auction_id',
        'remain_coupon',
        'total_coupon'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = [
        'nftAuctionInfo',
        'histories'
    ];

    /**
     * Get the nft auction info relates to user coupon.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nftAuctionInfo(): BelongsTo
    {
        return $this->belongsTo(NftAuctionInfo::class, 'nft_auction_id');
    }

    /**
     * Get all of the histories for the UserCoupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(UserCouponHistory::class, 'user_coupon_id', 'id');
    }
}
