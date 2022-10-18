<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class AuctionNft extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'auction_nfts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'type_id',
        'image_url',
        'nft_auction_id',
        'status',
    ];

    /**
     * Get the nft type relates to the auction nft.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nftType(): BelongsTo
    {
        return $this->belongsTo(NftType::class, 'type_id');
    }
}
