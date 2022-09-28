<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class TokenUnlockRule extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'token_unlock_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_code',
        'period',
        'unit',
        'unlock_percentages',
    ];

    /**
     * Get the rule codes.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ruleCode(): HasMany
    {
        return $this->hasMany(TokenUnlockRule::class, 'rule_code', 'rule_code');
    }
}
