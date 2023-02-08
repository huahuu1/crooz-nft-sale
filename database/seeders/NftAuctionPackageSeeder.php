<?php

namespace Database\Seeders;

use App\Models\NftAuctionPackage;
use Illuminate\Database\Seeder;
use Schema;

class NftAuctionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NftAuctionPackage::truncate();
        $nftAuctionPackages = [
            [
                'auction_id' => 2,
                'price' => 200,
                'unit_price' => 200,
                'destination_address' => config('defines.wallet.company_nft'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 2,
                'price' => 1000,
                'unit_price' => 200,
                'destination_address' => config('defines.wallet.company_nft'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 2,
                'price' => 300,
                'unit_price' => 200,
                'destination_address' => config('defines.wallet.company_nft'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 3,
                'price' => 100,
                'unit_price' => 100,
                'destination_address' => '0xde2fceF50CC71C18bA41c8091CF811BC7d688319',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 0.1,
                'unit_price' => 0.1,
                'destination_address' => '0xde2fceF50CC71C18bA41c8091CF811BC7d688319',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 0.2,
                'unit_price' => 0.2,
                'destination_address' => '0x921F78cf580d72E27AEb4D6f654Ef4431b7327591',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 0.3,
                'unit_price' => 0.3,
                'destination_address' => '0xcD67B40855C4209e11f13c198cA8DD470b889b6A1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 0.4,
                'unit_price' => 0.4,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'price' => 5000,
                'unit_price' => 5000,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'price' => 2500,
                'unit_price' => 2500,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'price' => 500,
                'unit_price' => 500,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'price' => 200,
                'unit_price' => 200,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'price' => 50,
                'unit_price' => 50,
                'destination_address' => '0x0E44F6Ce4db17C204fcaA7C35E1119Aa8212Cb431',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionPackage::insert($nftAuctionPackages);
        Schema::enableForeignKeyConstraints();
    }
}
