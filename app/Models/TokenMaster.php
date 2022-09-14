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
    ];

    public const USDT = 1;

    public const ETH = 2;

    public const GT = 3;

    public const BNB = 4;

    /**
     * Display information of the token master by id.
     *
     * @return \Illuminate\Http\Response
     */
    public static function getTokenMasterById($id)
    {
        return TokenMaster::find($id);
    }
}
