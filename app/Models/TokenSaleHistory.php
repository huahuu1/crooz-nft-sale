<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenSaleHistory extends Model
{
    use HasApiTokens;
    use HasFactory;

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

    public const PENDING_STATUS = 1;

    public const SUCCESS_STATUS = 2;

    public const FAILED_STATUS = 3;

    /**
     * Get ALl Pending Transactions
     *
     * @return mixed
     */
    public function pendingTokenSaleTransactions()
    {
        return $this->where('status', $this::PENDING_STATUS)
            ->where('created_at', '<', Carbon::now()
                ->subMinutes(1)
                ->toDateTimeString());
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the token master that owns the transaction.
     */
    public function token_master()
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }
}
