<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UnlockUserBalance extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'user_unlock_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token_id',
        'token_sale_id',
        'user_id',
        'amount_lock',
        'amount_lock_remain',
        'status',
    ];
}
