<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserCouponHistory;
use Carbon\Carbon;

class UserCouponService
{
    /**
     * has User Coupon function
     *
     * @param int $userId
     * @param int $auctionId
     * @return UserCoupon
     */
    public function hasUserCoupon($userId, $auctionId)
    {
        return UserCoupon::where('user_id', $userId)
            ->where('nft_auction_id', $auctionId)
            ->where('remain_coupon', '>', 0)
            ->first();
    }

    /**
     * create user coupon history
     *
     * @param int $couponId
     * @return void
     */
    public function createUserCouponHistory($couponId)
    {
        return UserCouponHistory::create([
            'user_coupon_id' => $couponId,
            'used_time' => Carbon::now()
        ]);
    }
}
