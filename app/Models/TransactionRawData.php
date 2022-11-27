<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TransactionRawData extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'transaction_raw_datas';

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
        'created_at',
        'updated_at'
    ];
}
