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
                'id' => 1,
                'auction_id' => 2,
                'network_id' => 1,
            ],
            [
                'id' => 2,
                'auction_id' => 2,
                'network_id' => 2,
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
