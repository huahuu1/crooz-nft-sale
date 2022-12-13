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
                'price' => 1,
                'unit_price' => 1,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 200,
                'unit_price' => 200,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 500,
                'unit_price' => 500,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 777,
                'unit_price' => 777,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 4,
                'price' => 1000,
                'unit_price' => 1000,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionPackage::insert($nftAuctionPackages);
        Schema::enableForeignKeyConstraints();
    }
}
