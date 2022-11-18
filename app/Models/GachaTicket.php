<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'ticket_type',
        'total_ticket',
        'remain_ticket'
    ];

    public const PAID_TICKET = 1;

    public const FREE_TICKET = 2;
}
