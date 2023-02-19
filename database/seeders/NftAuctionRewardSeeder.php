<?php

namespace Database\Seeders;

use App\Models\NftAuctionReward;
use Illuminate\Database\Seeder;
use Schema;

class NftAuctionRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NftAuctionReward::truncate();
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
            ],
            [
                'package_id' => 5,
                'nft_id' => null,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 6,
                'nft_id' => null,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 7,
                'nft_id' => null,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 8,
                'nft_id' => null,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 9,
                'nft_id' => 26,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 27,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 10,
                'nft_id' => 27,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 27,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 11,
                'nft_id' => 28,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 27,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 12,
                'nft_id' => 29,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 27,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 13,
                'nft_id' => 30,
                'ticket_quantity' => null,
                'nft_quantity' => null,
                'nft_delivery_id' => 27,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionReward::insert($nftAuctionRewards);
        Schema::enableForeignKeyConstraints();
    }
}
