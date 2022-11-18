<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class NetworkMaster extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'network_masters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chain_id',
        'rpc_urls',
        'block_explorer_urls',
        'chain_name',
        'unit'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['type:id,network_id,code,contract_wallet'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key'
    ];

    /**
     * Get the token master relates to network master.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function type(): HasMany
    {
        return $this->hasMany(TokenMaster::class, 'network_id', 'id');
    }
}
