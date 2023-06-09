<?php

namespace Database\Seeders;

use App\Models\NftAuctionXenoGachaId;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NftAuctionXenoGachaIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packageIds = [5, 6, 7, 8];
        $nftAuctionGachaIds = [];
        $gachaId = 0;
        foreach ($packageIds as $k => $packageId) {
            for ($i = 1; $i < 14; $i++) {
                $gachaId++;
                if ($gachaId <= 6) {
                    $xenoGachaId = ($k === 0 ? null : ($k + 1)) . $gachaId;
                } else {
                    $gachaId = 1;
                    $xenoGachaId = ($k === 0 ? null : ($k + 1)) . $gachaId;
                }
                if ($i === 13) {
                    $xenoGachaId = ($k === 0 ? null : ($k + 1)) . 7;
                }

                $nftAuctionGachaIds[] = [
                    'package_id' => $packageId,
                    'sale_time_id' => $i,
                    'xeno_gacha_id' => $xenoGachaId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            $gachaId = 0;
        }
        NftAuctionXenoGachaId::insert($nftAuctionGachaIds);
    }
}
