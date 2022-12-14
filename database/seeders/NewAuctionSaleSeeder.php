<?php

namespace Database\Seeders;

use App\Models\AuctionNetwork;
use App\Models\NftAuctionInfo;
use App\Models\NftAuctionPackage;
use App\Models\NftAuctionPackageStock;
use App\Models\NftAuctionReward;
use App\Models\NftDeliverySource;
use Illuminate\Database\Seeder;

class NewAuctionSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // nft delivery sources
        NftDeliverySource::create([
            'name' => '202212 NFT Auction'
        ]);

        // auction info
        $auctionInfos = [
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s', strtotime('+30 days', time())),
            'min_price' => '200',
            'name'  => '202212 nft auction',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        NftAuctionInfo::insert($auctionInfos);

        // auction network
        $auctionNetworks = [
            [
                'auction_id' => 4,
                'network_id' => 2,
            ]
        ];

        AuctionNetwork::insert($auctionNetworks);

        // Nft Auction Package
        $nftAuctionPackages = [
            [
                'id' => 5,
                'auction_id' => 4,
                'price' => 200,
                'unit_price' => 200,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'auction_id' => 4,
                'price' => 500,
                'unit_price' => 500,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 7,
                'auction_id' => 4,
                'price' => 777,
                'unit_price' => 777,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 8,
                'auction_id' => 4,
                'price' => 1000,
                'unit_price' => 1000,
                'destination_address' => '0x045508e6599Ce4f8a347D66b9dA2C3C4c655e394',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        NftAuctionPackage::insert($nftAuctionPackages);

        // nft package stock
        $NftAuctionPackageStocks = [
            [
                'package_id' => 6,
                'total' => 2000,
                'remain' => 2000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionPackageStock::insert($NftAuctionPackageStocks);

        // nft auction rewards
        $nftAuctionRewards = [
            [
                'package_id' => 5,
                'nft_id' => 7,
                'ticket_quantity' => 1,
                'nft_quantity' => 1,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 6,
                'nft_id' => 7,
                'ticket_quantity' => 6,
                'nft_quantity' => 5,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 7,
                'nft_id' => 7,
                'ticket_quantity' => 1,
                'nft_quantity' => 1,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 8,
                'nft_id' => 7,
                'ticket_quantity' => 1,
                'nft_quantity' => 1,
                'nft_delivery_id' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionReward::insert($nftAuctionRewards);

        // Xeno Class sale time
        $this->call([
            XenoClassSeeder::class,
            XenoClassSaleTimeSeeder::class,
            NftAuctionGachaIdSeeder::class,
            UserCouponSeeder::class
        ]);
    }
}