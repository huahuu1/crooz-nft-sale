<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('token_masters')->insert([
            [
                'id' => 1,
                'name' => 'TETHER',
                'code' => 'USDT',
                'description' => null,
                'status' => 1,
                'network_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'ETHEREUM',
                'code' => 'ETH',
                'description' => null,
                'status' => 1,
                'network_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'GT',
                'code' => 'GT',
                'description' => null,
                'status' => 1,
                'network_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'name' => 'BINANCE COIN',
                'code' => 'BNB',
                'description' => null,
                'status' => 1,
                'network_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'name' => 'BINANCE USD',
                'code' => 'BUSD',
                'description' => null,
                'status' => 1,
                'network_id' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'name' => 'JPY MONEY',
                'code' => 'JPY',
                'description' => null,
                'status' => 0,
                'network_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'name' => 'Binance-Peg BSC-USD',
                'code' => 'USDT',
                'description' => null,
                'status' => 1,
                'network_id' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
