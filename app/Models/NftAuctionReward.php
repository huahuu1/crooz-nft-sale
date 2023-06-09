<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionReward extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nft_auction_rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id',
        'nft_id',
        'ticket_quantity',
        'nft_quantity',
        'nft_delivery_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['Nft', 'Delivery'];

    /**
     * Get Nft into Reward Auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Nft(): BelongsTo
    {
        return $this->belongsTo(Nft::class, 'nft_id', 'nft_id');
    }

    /**
     * Get Nft Delivery Source into Reward Auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Delivery(): BelongsTo
    {
        return $this->belongsTo(NftDeliverySource::class, 'nft_delivery_id', 'id');
    }
}
