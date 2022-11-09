<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TicketUsedHistory extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'ticket_used_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gacha_ticket_id',
        'used_quantity',
        'used_time'
    ];
}
