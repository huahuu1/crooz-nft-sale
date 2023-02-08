<?php

namespace Database\Seeders;

use App\Models\NftAuctionPackageStock;
use Illuminate\Database\Seeder;
use Schema;

class NftAuctionPackageStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NftAuctionPackageStock::truncate();
        $NftAuctionPackageStocks = [
            [
                'package_id' => 3,
                'total' => 100,
                'remain' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 6,
                'total' => 100,
                'remain' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'package_id' => 9,
                'total' => 100,
                'remain' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        NftAuctionPackageStock::insert($NftAuctionPackageStocks);
        Schema::enableForeignKeyConstraints();
    }
}
