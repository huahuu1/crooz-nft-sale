<?php

namespace Database\Seeders;

use App\Models\AuctionNetwork;
use App\Models\NftAuctionPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HikaruAuctionSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert nft auction package
        $nftAuctionPackages = [
            [
                'auction_id' => 3,
                'price' => 100,
                'unit_price' => 100,
                'destination_address' => config('defines.wallet.company_nft'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        NftAuctionPackage::insert($nftAuctionPackages);

        // insert auction nfts
        $auctionNfts = [
            [
                'auction_id' => 3,
                'network_id' => 2,
            ]
        ];

        AuctionNetwork::insert($auctionNfts);

        //
    }
}
