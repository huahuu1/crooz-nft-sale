<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenSaleHistory extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'token_sale_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'token_sale_id',
        'amount',
        'status',
        'tx_hash',
    ];

    const PENDING_STATUS = 1;
    const SUCCESS_STATUS = 2;
    const FAILED_STATUS = 3;

    /**
     * Get ALl Pending Transactions
     *
     * @return mixed
     */
    public function pendingTokenSaleTransactions()
    {
        return $this->where('status', 'PROCESSING')->get();
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
