<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class XenoClass extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'xeno_classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class'
    ];

    /**
     * Get the package that owns the nft class.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Package(): BelongsTo
    {
        return $this->belongsTo(NftAuctionPackage::class, 'package_id');
    }

    /**
     * Get the nft that owns the nft class.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Nft(): BelongsTo
    {
        return $this->belongsTo(Nft::class, 'xeno_class', 'nft_id');
    }
}