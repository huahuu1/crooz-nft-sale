<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuctionNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auction_networks')->insert([
            [
                'id' => 1,
                'auction_id' => 2,
                'network_id' => 1,
            ],
            [
                'id' => 2,
                'auction_id' => 2,
                'network_id' => 4,
            ],
            [
                'id' => 3,
                'auction_id' => 2,
                'network_id' => 5,
            ]
        ]);
    }
}
