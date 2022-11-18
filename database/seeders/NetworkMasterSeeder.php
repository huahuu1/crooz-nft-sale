<?php

namespace Database\Seeders;

use App\Models\NetworkMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Schema;

class NetworkMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NetworkMaster::truncate();
        NetworkMaster::insert($this->getArrayData());
        Schema::enableForeignKeyConstraints();
    }

    public function getArrayData()
    {
        switch (config('app.env')) {
            case 'local':
                return [
                    [
                        'id' => 1,
                        'chain_id' => '5',
                        'rpc_urls' => 'https://goerli.infura.io/v3/',
                        'block_explorer_urls' => 'https://goerli.etherscan.io/',
                        'chain_name' => 'Goerli Test Network',
                        'unit' => 'ETH',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 2,
                        'chain_id' => '97',
                        'rpc_urls' => 'https://bsc-testnet.public.blastapi.io/',
                        'block_explorer_urls' => 'https://testnet.bscscan.com/',
                        'chain_name' => 'Binance Smart Chain Testnet',
                        'unit' => 'BNB',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                ];
            case 'production':
                return [
                    [
                        'id' => 1,
                        'chain_id' => '1',
                        'rpc_urls' => 'https://mainnet.infura.io/v3/',
                        'block_explorer_urls' => 'https://etherscan.io/',
                        'chain_name' => 'Ethereum Mainnet',
                        'unit' => 'ETH',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 2,
                        'chain_id' => '56',
                        'rpc_urls' => 'https://bsc-dataseed.binance.org/',
                        'block_explorer_urls' => 'https://bscscan.com/',
                        'chain_name' => 'Binance Smart Chain',
                        'unit' => 'BNB',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                ];
        }
    }
}
