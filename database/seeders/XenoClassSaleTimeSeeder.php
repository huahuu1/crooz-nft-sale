<?php

namespace Database\Seeders;

use App\Models\XenoClassSaleTime;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class XenoClassSaleTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $xenoClassesTime = [
            [
                'auction_id' => 4,
                'xeno_class_id' => 1,
                'start_time' => Carbon::create('2022', '12', '27', '20'),
                'end_time'  => Carbon::create('2022', '12', '28', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 2,
                'start_time' => Carbon::create('2022', '12', '28', '20'),
                'end_time'  => Carbon::create('2022', '12', '29', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 3,
                'start_time' => Carbon::create('2022', '12', '29', '20'),
                'end_time'  => Carbon::create('2022', '12', '30', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 4,
                'start_time' => Carbon::create('2022', '12', '30', '20'),
                'end_time'  => Carbon::create('2022', '12', '31', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 5,
                'start_time' => Carbon::create('2022', '12', '31', '20'),
                'end_time'  => Carbon::create('2023', '01', '01', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 6,
                'start_time' => Carbon::create('2023', '01', '01', '20'),
                'end_time'  => Carbon::create('2023', '01', '02', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 1,
                'start_time' => Carbon::create('2023', '01', '02', '20'),
                'end_time'  => Carbon::create('2023', '01', '03', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 2,
                'start_time' => Carbon::create('2023', '01', '03', '20'),
                'end_time'  => Carbon::create('2023', '01', '04', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 3,
                'start_time' => Carbon::create('2023', '01', '04', '20'),
                'end_time'  => Carbon::create('2023', '01', '05', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 4,
                'start_time' => Carbon::create('2023', '01', '05', '20'),
                'end_time'  => Carbon::create('2023', '01', '06', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 5,
                'start_time' => Carbon::create('2023', '01', '06', '20'),
                'end_time'  => Carbon::create('2023', '01', '07', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 6,
                'start_time' => Carbon::create('2023', '01', '07', '20'),
                'end_time'  => Carbon::create('2023', '01', '08', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'auction_id' => 4,
                'xeno_class_id' => 7,
                'start_time' => Carbon::create('2023', '01', '08', '20'),
                'end_time'  => Carbon::create('2023', '01', '10', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        XenoClassSaleTime::insert($xenoClassesTime);
    }
}