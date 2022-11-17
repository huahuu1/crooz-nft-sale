<?php

namespace Database\Seeders;

use App\Models\NftAuctionReward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NftAuctionRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nftAuctionRewards = [
            [
                'package_id' => 1,
                'nft_id' => 7,
                'ticket_quantity' => 1,
                'nft_quantity' => 1,
                'nft_delivery_id' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 2,
                'nft_id' => 7,
                'ticket_quantity' => 6,
                'nft_quantity' => 5,
                'nft_delivery_id' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 3,
                'nft_id' => 7,
                'ticket_quantity' => 1,
                'nft_quantity' => 1,
                'nft_delivery_id' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        NftAuctionReward::insert($nftAuctionRewards);
    }
}
