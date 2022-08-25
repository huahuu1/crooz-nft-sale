<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserBalance extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'user_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'amount_total',
        'amount_lock',
    ];

    protected $appends = ['amount_available'];

    public const USDT = 1;
    public const ETH = 2;
    public const GT = 3;

    /**
     * Get the token relates to user balanace.
     */
    public function token_master()
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }

    /**
     * Calculate user's available amount.
     *
     * @return amount
     */
    protected function getAmountAvailableAttribute()
    {
        return (string)($this->amount_total - $this->amount_lock);
    }
}
