<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenMaster extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'token_masters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'network_id'
    ];

    public const USDT = 1;

    public const ETH = 2;

    public const GT = 3;

    public const BNB = 4;

    /**
     * Display information of the token master.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTokenMasters()
    {
        return TokenMaster::select('id', 'name', 'code', 'description', 'status')->where('status', 1)->get();
    }

    /**
     * Display information of the token master by id.
     *
     * @return \App\Models\TokenMaster
     */
    public static function getTokenMasterById($id)
    {
        return TokenMaster::select(
            'id',
            'name',
            'code',
            'description',
            'status',
            'network_id'
        )
        ->find($id);
    }

    /**
     * Display information of the token master by id.
     *
     * @return \App\Models\TokenMaster
     */
    public static function getTokenMastersWithNetwork()
    {
        return TokenMaster::select(
            'id',
            'name',
            'code',
            'description',
            'status',
            'network_id'
        )
        ->with('networkMaster:id,chain_id,rpc_urls,block_explorer_urls,chain_name,unit,contract_wallet')
        ->get();
    }

    /**
     * Get the network master relates to token master.
     */
    public function networkMaster()
    {
        return $this->belongsTo(NetworkMaster::class, 'network_id');
    }
}
