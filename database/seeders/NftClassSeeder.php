<?php

namespace Database\Seeders;

use App\Models\NftClass;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Schema;

class NftClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NftClass::truncate();
        $nftClasses = [
            [
                'sale_date' => Carbon::create('2022', '12', '27', '20'),
                'package_id' => 5,
                'xeno_class' => 23,
                'xeno_gacha_id' => 1,
                'weapon_gacha_id' => 11,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2022', '12', '28', '20'),
                'package_id' => 6,
                'xeno_class' => 24,
                'xeno_gacha_id' => 2,
                'weapon_gacha_id' => 12,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2022', '12', '29', '20'),
                'package_id' => 7,
                'xeno_class' => 25,
                'xeno_gacha_id' => 3,
                'weapon_gacha_id' => 13,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2022', '12', '30', '20'),
                'package_id' => 8,
                'xeno_class' => 29,
                'xeno_gacha_id' => 4,
                'weapon_gacha_id' => 14,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2022', '12', '31', '20'),
                'package_id' => 5,
                'xeno_class' => 26,
                'xeno_gacha_id' => 5,
                'weapon_gacha_id' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '01', '20'),
                'package_id' => 6,
                'xeno_class' => 30,
                'xeno_gacha_id' => 6,
                'weapon_gacha_id' => 16,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '02', '20'),
                'package_id' => 7,
                'xeno_class' => 23,
                'xeno_gacha_id' => 1,
                'weapon_gacha_id' => 11,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '03', '20'),
                'package_id' => 8,
                'xeno_class' => 24,
                'xeno_gacha_id' => 2,
                'weapon_gacha_id' => 12,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '04', '20'),
                'package_id' => 5,
                'xeno_class' => 25,
                'xeno_gacha_id' => 3,
                'weapon_gacha_id' => 13,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '05', '20'),
                'package_id' => 6,
                'xeno_class' => 29,
                'xeno_gacha_id' => 4,
                'weapon_gacha_id' => 14,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '06', '20'),
                'package_id' => 7,
                'xeno_class' => 26,
                'xeno_gacha_id' => 5,
                'weapon_gacha_id' => 15,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '07', '20'),
                'package_id' => 8,
                'xeno_class' => 30,
                'xeno_gacha_id' => 6,
                'weapon_gacha_id' => 16,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '08', '20'),
                'package_id' => 5,
                'xeno_class' => 31,
                'xeno_gacha_id' => 7,
                'weapon_gacha_id' => 17,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '09', '20'),
                'package_id' => 6,
                'xeno_class' => 31,
                'xeno_gacha_id' => 7,
                'weapon_gacha_id' => 17,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'sale_date' => Carbon::create('2023', '01', '10', '20'),
                'package_id' => 7,
                'xeno_class' => 31,
                'xeno_gacha_id' => 7,
                'weapon_gacha_id' => 17,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        NftClass::insert($nftClasses);
        Schema::enableForeignKeyConstraints();
    }
}
