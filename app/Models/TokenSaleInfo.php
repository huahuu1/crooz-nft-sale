<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
     * Get token unlock rule
     *
     * @return TokenUnlockRule
     */
    public function tokenUnlockRules(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $tokenRule = TokenUnlockRule::select('rule_code')->where('id', '=', $attributes['rule_id'])->first();

                return TokenUnlockRule::where('rule_code', '=', $tokenRule->rule_code)->get();
            }
        );
    }
}
