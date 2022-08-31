<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TokenSaleInfo extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'token_sale_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lock_id',
        'start_date',
        'end_date',
        'total',
        'price',
        'status',
    ];

    /**
     * Get the lock info that owns the token sale.
     */
    public function lock_info()
    {
        return $this->belongsTo(LockInfo::class, 'lock_id');
    }
}
