<?php

namespace Database\Seeders;

use App\Models\UserCoupon;
use Illuminate\Database\Seeder;
use Schema;

class UserCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        UserCoupon::truncate();
        // user coupon
        $userCoupon = [
            [
                'user_id' => 1,
                'nft_auction_id' => 4,
                'remain_coupon' => 10,
                'total_coupon' => 10,
                'discount_percentage' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 1,
                'nft_auction_id' => 5,
                'remain_coupon' => 10,
                'total_coupon' => 10,
                'discount_percentage' => 50,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        UserCoupon::insert($userCoupon);
        Schema::enableForeignKeyConstraints();
    }
}
