<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class NftAuctionHistory extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'nft_auction_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token_id',
        'nft_auction_id',
        'amount',
        'status',
        'tx_hash',
        'payment_method'
    ];

    public const PENDING_STATUS = 1;

    public const SUCCESS_STATUS = 2;

    public const FAILED_STATUS = 3;

    public const METHOD_CRYPTO = 1;

    public const METHOD_FIAT = 2;

    /**
     * Get ALl Pending Transactions
     *
     * @return mixed
     */
    public function pendingNftAuctionTransactions()
    {
        return $this->where('status', $this::PENDING_STATUS)
                    ->where('created_at', '<', Carbon::now()
                    ->subMinutes(1)
                    ->toDateTimeString());
    }

    /**
     * Get the user that owns the transaction.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the token master that owns the transaction.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tokenMaster(): BelongsTo
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }
}
