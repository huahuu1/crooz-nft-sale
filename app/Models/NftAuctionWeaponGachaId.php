<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NftAuctionWeaponGachaId extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nft_auction_weapon_gacha_ids';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nft_id', 'weapon_gacha_id'];
}