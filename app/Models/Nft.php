<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nftType(): BelongsTo
    {
        return $this->belongsTo(NftType::class, 'type_id');
    }
}
