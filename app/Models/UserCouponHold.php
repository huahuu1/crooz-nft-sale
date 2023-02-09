<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCouponHold extends Model
{
    use HasFactory;

    protected $table = 'user_coupon_holds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_coupon_id',
        'package_id',
        'purchased_time'
    ];
}
