<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function rule_code()
    {
        return $this->hasMany(TokenUnlockRule::class, 'rule_code');
    }
}
