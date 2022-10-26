<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PrivateUserUnlockBalance extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'private_user_unlock_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token_id',
        'token_type',
        'investor_classification',
        'wallet_address',
        'token_unlock_volume',
        'unlock_date',
        'status',
    ];

    /**
     * Get the token master that owns the private user unlock balance.
     */
    public function tokenMaster()
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }
}
