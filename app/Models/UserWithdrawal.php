<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class UserWithdrawal extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'user_withdrawals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'private_unlock_id',
        'amount',
        'request_time',
        'status',
    ];

    public const WAITING_STATUS = 1;

    public const OPEN_STATUS = 2;

    public const PROCESSING_STATUS = 3;

    public const SUCCESS_STATUS = 4;

    public const FAIL_STATUS = 5;

    /**
     * Get the private unlock relates to user withdrawal.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function privateUnlock(): BelongsTo
    {
        return $this->belongsTo(PrivateUserUnlockBalance::class, 'private_unlock_id');
    }
}
