<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            NetworkMasterSeeder::class,
            TokenMasterSeeder::class,
            UserSeeder::class,
            NftTypeSeeder::class,
            AdminSeeder::class,
            NftSeeder::class,
            GxePartnerUserSeeder::class,
            NftDeliverySourceSeeder::class,
            ExChangeRateSeeder::class,
            AuctionNetworkSeeder::class
        ]);

        \App\Models\NftAuctionInfo::factory(1)->create();
    }
}
