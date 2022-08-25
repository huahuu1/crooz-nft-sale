<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class LockInfo extends Model
{
    use HasApiTokens;
    use HasFactory;

    protected $table = 'lock_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lock_day',
        'unlock_percentages',
        'status',
    ];
}
