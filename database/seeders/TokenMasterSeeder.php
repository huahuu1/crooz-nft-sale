<?php

namespace Database\Seeders;

use App\Models\TokenMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Schema;

class TokenMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TokenMaster::truncate();
        TokenMaster::insert($this->getArrayData());
        Schema::enableForeignKeyConstraints();
    }

    public function getArrayData()
    {
        switch (config('app.env')) {
            case 'local':
                return [
                [
                    'id' => 1,
                    'name' => 'TETHER',
                    'code' => 'USDT',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 1,
                    'contract_wallet' => '0x50c4c585912B0B9EB2E382a1a8c96bcA9b112441',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'name' => 'ETHEREUM',
                    'code' => 'ETH',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 1,
                    'contract_wallet' => null,
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
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'name' => 'BINANCE COIN',
                    'code' => 'BNB',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'name' => 'BINANCE USD',
                    'code' => 'BUSD',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => '0x337610d27c682E347C9cD60BD4b3b107C9d34dDd',
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
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 7,
                    'name' => 'Binance-Peg BSC-USD',
                    'code' => 'USDT',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => '0xed24fc36d5ee211ea25a80239fb8c4cfd80f12ee',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ];
            case 'production':
                return [
                [
                    'id' => 1,
                    'name' => 'TETHER',
                    'code' => 'USDT',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 1,
                    'contract_wallet' => '0xdac17f958d2ee523a2206206994597c13d831ec7',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'name' => 'ETHEREUM',
                    'code' => 'ETH',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 1,
                    'contract_wallet' => null,
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
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'name' => 'BINANCE COIN',
                    'code' => 'BNB',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'name' => 'BINANCE USD',
                    'code' => 'BUSD',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => '0xe9e7cea3dedca5984780bafc599bd69add087d56',
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
                    'contract_wallet' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 7,
                    'name' => 'Binance-Peg BSC-USD',
                    'code' => 'USDT',
                    'description' => null,
                    'status' => 1,
                    'network_id' => 2,
                    'contract_wallet' => '0x55d398326f99059fF775485246999027B3197955',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ];
        }
    }
}
