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
        $userCoupon = [
            [
                'user_id' => 1,
                'nft_auction_id' => 4,
                'remain_coupon' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'user_id' => 2,
                'nft_auction_id' => 4,
                'remain_coupon' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        UserCoupon::insert($userCoupon);
        Schema::enableForeignKeyConstraints();
    }
}
