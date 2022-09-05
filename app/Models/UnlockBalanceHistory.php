<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UnlockBalanceHistory extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'unlock_balance_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unlock_id',
        'amount',
        'release_token_date',
    ];

    /**
     * Get the unlock user balance that owns the unlock balance histories.
     */
    public function unlock_user_balance()
    {
        return $this->belongsTo(UnlockUserBalance::class, 'unlock_id');
    }
}
