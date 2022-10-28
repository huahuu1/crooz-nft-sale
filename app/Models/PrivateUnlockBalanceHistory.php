<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class PrivateUnlockBalanceHistory extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'private_unlock_balance_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unlock_id',
        'amount',
        'unlock_token_date',
        'admin_id',
        'network_id',
        'tx_hash',
        'status',
    ];

    public const PENDING_STATUS = 1;

    public const SUCCESS_STATUS = 2;

    public const FAIL_STATUS = 3;

    /**
     * Get the unlock user balance that owns the unlock balance histories.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function privateUserUnlockBalance(): BelongsTo
    {
        return $this->belongsTo(PrivateUserUnlockBalance::class, 'unlock_id');
    }

    /**
     * Get the admin relates to unlock balance history.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(admin::class, 'admin_id');
    }
}
