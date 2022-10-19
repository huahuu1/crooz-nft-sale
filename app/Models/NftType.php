<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Sanctum\HasApiTokens;

class NftType extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'nft_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get all of the nfts for the NftType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nfts(): HasMany
    {
        return $this->hasMany(Nft::class, 'nft_type', 'id');
    }

    /**
     * Get the AuctionNft relate to NftType through Nft
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function auctionNfts(): HasManyThrough
    {
        //Params:
        //Directly related table(Nft)
        //Indirect related table(AuctionNft)
        //Foreign key on the AuctionNft table
        //Foreign key on the Nft table
        //Local key on the NftType table
        //Local key on the AuctionNft table
        return $this->hasManyThrough(Nft::class, AuctionNft::class, 'nft_id', 'nft_id', 'id', 'nft_id');
    }
}
