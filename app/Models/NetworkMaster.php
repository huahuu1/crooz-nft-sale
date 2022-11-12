<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'unit',
        'contract_wallet'
    ];
}
