<?php

namespace Database\Seeders;

use App\Models\GachaTicketExchangeTime;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Schema;

class GachaTicketExchangeTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        GachaTicketExchangeTime::truncate();

        $nftAuctionWeaponGachaIds = [
            [
                'auction_id' => 4,
                'start_time' => Carbon::create('2022', '12', '27', '20'),
                'end_time'  => Carbon::create('2023', '01', '10', '19', '59', '59'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'auction_id' => 5,
                'start_time' => Carbon::create('2023-02-15'),
                'end_time'  => Carbon::create('2023-02-22'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        GachaTicketExchangeTime::insert($nftAuctionWeaponGachaIds);
        Schema::enableForeignKeyConstraints();
    }
}
