<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'amount',
        'request_time',
        'status',
    ];

    public const REQUESTING_STATUS = 1;
    public const PROCESSING_STATUS = 2;
    public const CLOSE_STATUS = 3;
    public const FORCECLOSE_STATUS = 4;
    public const REJECT_STATUS = 5;
}
