<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NetworkMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('network_masters')->insert([
            [
                'id' => 1,
                'chain_id' => '56',
                'rpc_urls' => 'https://bsc-dataseed.binance.org/',
                'block_explorer_urls' => 'https://bscscan.com/',
                'chain_name' => 'Binance Smart Chain',
                'unit' => 'BNB',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'chain_id' => '97',
                'rpc_urls' => 'https://data-seed-prebsc-1-s1.binance.org:8545/',
                'block_explorer_urls' => 'https://testnet.bscscan.com/',
                'chain_name' => 'Binance Smart Chain Testnet',
                'unit' => 'BNB',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'chain_id' => '3',
                'rpc_urls' => 'https://ropsten.infura.io/v3/',
                'block_explorer_urls' => 'https://ropsten.etherscan.io/',
                'chain_name' => 'Ropsten Test Network',
                'unit' => 'RopstenETH',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'chain_id' => '1',
                'rpc_urls' => 'https://mainnet.infura.io/v3/',
                'block_explorer_urls' => 'https://etherscan.io/',
                'chain_name' => 'Ethereum Mainnet',
                'unit' => 'ETH',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'chain_id' => '5',
                'rpc_urls' => 'https://goerli.infura.io/v3/',
                'block_explorer_urls' => 'https://goerli.etherscan.io/',
                'chain_name' => 'Goerli Test Network',
                'unit' => 'GoerliETH',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
