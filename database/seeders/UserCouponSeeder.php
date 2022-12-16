<?php

namespace Database\Seeders;

use App\Models\UserCoupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user coupon
        $userCoupon = [
            [
                'user_id' => 1,
                'nft_auction_id' => 4,
                'remain_coupon' => 10,
                'total_coupon' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        UserCoupon::insert($userCoupon);
    }
}
