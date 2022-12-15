<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NftAuctionXenoGachaId extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nft_auction_xeno_gacha_ids';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['xenoSaleTime:id,auction_id,xeno_class_id,start_time,end_time'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_id',
        'sale_time_id',
        'xeno_gacha_id',
    ];

    /**
     * Get all of the xenoSaleTime for the NftAuctionGachaId
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function xenoSaleTime(): BelongsTo
    {
        return $this->belongsTo(XenoClassSaleTime::class, 'sale_time_id', 'id');
    }
}