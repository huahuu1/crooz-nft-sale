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
            TokenMasterSeeder::class,
            UserSeeder::class,
            NftTypeSeeder::class,
            AdminSeeder::class,
            TokenUnlockRuleSeeder::class,
            NftSeeder::class,
            GxePartnerUserSeeder::class,
        ]);

        \App\Models\TokenSaleInfo::factory(1)->create();
        \App\Models\NftAuctionInfo::factory(1)->create();
    }
}
