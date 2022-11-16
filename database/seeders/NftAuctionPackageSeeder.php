<?php

namespace Database\Seeders;

use App\Models\NftAuctionPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NftAuctionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            ]
        ];
        NftAuctionPackage::insert($nftAuctionPackages);
    }
}
