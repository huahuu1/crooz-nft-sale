<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Nft extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'nfts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_no',
        'type_id',
        'nft_id',
        'nft_owner_id',
        'tx_hash',
        'image_url',
        'status',
    ];

    /**
     * Get the nft type relates to the nft.
     */
    public function nft_type()
    {
        return $this->belongsTo(NftType::class, 'type_id');
    }
}