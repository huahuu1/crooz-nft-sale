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
        'wallet_address',
        'nft_id',
        'nft_delivery_source_id',
        'status',
    ];

    /**
     * Get the nft relates to the auction nft.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nfts(): BelongsTo
    {
        return $this->belongsTo(Nft::class, 'nft_id', 'nft_id');
    }
}
