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
        'rule_id',
        'start_date',
        'end_date',
        'total',
        'price',
        'status',
    ];

    /**
     * Get the rule info that owns the token sale.
     */
    public function token_unlock_rule()
    {
        return $this->hasMany(TokenUnlockRule::class, 'id', 'rule_id')->with('rule_code:id,rule_code,period,unit,unlock_percentages');
    }
}
