<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionRawHistory extends Model
{
    use HasFactory;

    protected $table = 'transaction_raw_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'chain',
        'tx_hash',
        'from',
        'to',
        'token',
        'value',
        'timestamp',
    ];
}
