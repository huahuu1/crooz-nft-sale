<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GachaTicketExchangeTime extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gacha_ticket_exchange_times';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auction_id',
        'start_time',
        'end_time'
    ];

    /**
     * Get the ticket exchange time by auction id.
     *
     * @return \App\Models\GachaTicketExchangeTime
     */
    public static function getTicketExchangeTimeByAuctionId($auctionId)
    {
        return GachaTicketExchangeTime::select('id', 'auction_id', 'start_time', 'end_time')->where('auction_id', 4)->first();
    }
}
