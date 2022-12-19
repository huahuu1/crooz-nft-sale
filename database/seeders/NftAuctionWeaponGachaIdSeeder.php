<?php

namespace Database\Seeders;

use App\Models\NftAuctionWeaponGachaId;
use Illuminate\Database\Seeder;
use Schema;

class NftAuctionWeaponGachaIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NftAuctionWeaponGachaId::truncate();

        $nftAuctionWeaponGachaIds = [
            [
                'id' => 1,
                'nft_id' => 23,
                'weapon_gacha_id' => 31
            ],
            [
                'id' => 2,
                'nft_id' => 24,
                'weapon_gacha_id' => 30
            ],
            [
                'id' => 3,
                'nft_id' => 25,
                'weapon_gacha_id' => 29
            ],
            [
                'id' => 4,
                'nft_id' => 26,
                'weapon_gacha_id' => 28
            ],
            [
                'id' => 5,
                'nft_id' => 27,
                'weapon_gacha_id' => 27
            ],
            [
                'id' => 6,
                'nft_id' => 28,
                'weapon_gacha_id' => 26
            ],
            [
                'id' => 7,
                'nft_id' => 29,
                'weapon_gacha_id' => 25
            ],
            [
                'id' => 8,
                'nft_id' => 30,
                'weapon_gacha_id' => 24
            ],
            [
                'id' => 9,
                'nft_id' => 31,
                'weapon_gacha_id' => 23
            ]
        ];
        NftAuctionWeaponGachaId::insert($nftAuctionWeaponGachaIds);
        Schema::enableForeignKeyConstraints();
    }
}
