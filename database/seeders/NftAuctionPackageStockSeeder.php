<?php

namespace Database\Seeders;

use App\Models\NftAuctionPackageStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NftAuctionPackageStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $NftAuctionPackageStocks = [
            [
                'package_id' => 3,
                'total' => 100,
                'remain' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionPackageStock::insert($NftAuctionPackageStocks);
    }
}
