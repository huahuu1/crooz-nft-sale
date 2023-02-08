<?php

namespace Database\Seeders;

use App\Models\AuctionNetwork;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Schema;

class AuctionNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AuctionNetwork::truncate();

        DB::table('auction_networks')->insert([
            [
                'auction_id' => 2,
                'network_id' => 1,
            ],
            [
                'auction_id' => 2,
                'network_id' => 2,
            ],
            [
                'auction_id' => 3,
                'network_id' => 2,
            ],
            [
                'auction_id' => 4,
                'network_id' => 2,
            ],
            [
                'auction_id' => 5,
                'network_id' => 2,
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
