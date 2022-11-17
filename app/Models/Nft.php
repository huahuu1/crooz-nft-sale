<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Nft extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'nfts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nft_id',
        'nft_type',
        'name',
        'image_url',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * Get the nft type relates to the nft.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nftType(): BelongsTo
    {
        return $this->belongsTo(NftType::class, 'nft_type', 'id');
    }

    /**
     * Get all of the nfts for the NftType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctionNfts(): HasMany
    {
        return $this->hasMany(AuctionNft::class, 'nft_id', 'nft_id');
    }

    /**
     * Get random nfts.
     *
     * @return \App\Models\Nft
     */
    public static function getRandomNfts()
    {
        return Nft::select('nft_id')->pluck('nft_id')->random(3)->toArray();
    }
}
