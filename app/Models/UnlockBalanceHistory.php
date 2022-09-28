<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unlockUserBalance(): BelongsTo
    {
        return $this->belongsTo(UnlockUserBalance::class, 'unlock_id');
    }
}
