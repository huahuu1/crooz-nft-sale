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
        'next_run_date',
        'status',
        'current_order_unlock',
    ];

    /**
     * Get ALl unlock user balance
     *
     * @return mixed
     */
    public function getUnlockUserBalances()
    {
        return UnlockUserBalance::where('status', 1)->with(['token_master', 'token_sale']);
    }

    /**
     * Get the token master that owns the unlock user balance.
     */
    public function token_master()
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }

    /**
     * Get the token sale id that owns the unlock user balance.
     */
    public function token_sale()
    {
        return $this->belongsTo(TokenSaleInfo::class, 'token_sale_id');
    }
}
