<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class GachaTicket extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'gacha_tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nft_auction_id',
        'ticket_type',
        'total_ticket',
        'remain_ticket',
        'created_at',
        'updated_at'
    ];

    public const PAID_TICKET = 1;

    public const FREE_TICKET = 2;

    /**
     * Get the nft auction info relates to gacha ticket.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nftAuctionInfo(): BelongsTo
    {
        return $this->belongsTo(NftAuctionInfo::class, 'nft_auction_id');
    }
}
