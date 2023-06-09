<?php

namespace App\Console\Commands;

use App\Services\UserCouponService;
use Illuminate\Console\Command;

class RefundUserCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:coupon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund User Coupon Command';

    /**
     * User Coupon Service variable
     *
     * @var App\Services\UserCouponService
     */
    protected $userCouponService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserCouponService $userCouponService)
    {
        parent::__construct();
        $this->userCouponService = $userCouponService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        info("Start RefundUserCoupon");
        $this->refundUserCoupon();
        info("End RefundUserCoupon");
    }

    /**
     * Refund User Coupon
     *
     * @return void
     */
    public function refundUserCoupon()
    {
        // timeout of holding coupon
        $time = config('defines.coupon.timeout');
        // get all coupon hold that greater than 3 hours
        $couponHolds = $this->userCouponService->getUserCouponHolds($time);
        // refund coupon to user and delete coupon hold
        if (!empty($couponHolds)) {
            foreach ($couponHolds as $couponHold) {
                // get user coupon
                $userCoupon = $this->userCouponService->getUserCouponById($couponHold->user_coupon_id);
                $userCoupon->remain_coupon += 1;
                $userCoupon->update();
                $couponHold->delete();
            }
        }
    }
}
