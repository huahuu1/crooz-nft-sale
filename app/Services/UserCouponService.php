<?php

namespace App\Services;

use App\Models\UserCoupon;
use App\Models\UserCouponHistory;
use App\Models\UserCouponHold;
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
     * get User Coupon
     *
     * @param int $userId
     * @param int $auctionId
     * @return UserCoupon
     */
    public function getUserCoupon($userId, $auctionId)
    {
        return UserCoupon::where('user_id', $userId)
            ->where('nft_auction_id', $auctionId)
            ->first();
    }

    /**
     * get User Coupon by id
     *
     * @param int $id
     * @return UserCoupon
     */
    public function getUserCouponById($id)
    {
        return UserCoupon::where('id', $id)->first();
    }

    /**
     * get User Coupon Hold
     *
     * @param int $couponId, $packageId
     * @return UserCouponHold
     */
    public function getUserCouponHold($couponId, $packageId)
    {
        return UserCouponHold::where('user_coupon_id', $couponId)
            ->where('package_id', $packageId)
            ->first();
    }

    /**
     * get User Coupon Hold that greater than 3 hours
     * @return UserCouponHold
     */
    public function getUserCouponHolds($time)
    {
        return UserCouponHold::where('purchased_time', '<=', Carbon::now()->subHours($time)->toDateTimeString())
            ->get();
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

    /**
     * create user coupon history by date
     *
     * @param int $couponId, $purchaseTime
     * @return void
     */
    public function createUserCouponHistoryByDate($couponId, $purchaseTime)
    {
        return UserCouponHistory::create([
            'user_coupon_id' => $couponId,
            'used_time' => $purchaseTime
        ]);
    }

    /**
     * create user coupon hold
     *
     * @param int $couponId, $packageId
     * @return void
     */
    public function createUserCouponHold($couponId, $packageId)
    {
        return UserCouponHold::create([
            'user_coupon_id' => $couponId,
            'package_id' => $packageId,
            'purchased_time' => Carbon::now()
        ]);
    }
}
