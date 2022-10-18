<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this->hasMany(AuctionNft::class, 'type_id', 'id');
    }
}
