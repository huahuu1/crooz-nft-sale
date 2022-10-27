<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Get the token relates to user balance.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tokenMaster(): BelongsTo
    {
        return $this->belongsTo(TokenMaster::class, 'token_id');
    }

    /**
     * Calculate user's available amount.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function amountAvailable(): Attribute
    {
        return new Attribute(
            get: fn () => (string) ($this->amount_total - $this->amount_lock),
        );
    }
}
