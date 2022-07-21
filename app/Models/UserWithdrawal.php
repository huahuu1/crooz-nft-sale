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
}
