<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserWithdrawal extends Model
{
    use HasApiTokens, HasFactory;

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

    const REQUESTING_STATUS = 1;
    const PROCESSING_STATUS = 2;
    const CLOSE_STATUS = 3;
    const FORCECLOSE_STATUS = 4;
    const REJECT_STATUS = 5;
}
