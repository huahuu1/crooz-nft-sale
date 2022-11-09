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
        DB::table('network_masters')->insert(
            $this->getArrayData()
        );
    }

    public function getArrayData()
    {
        switch (env('APP_ENV')) {
            case 'local':
                return [
                    [
                        'id' => 1,
                        'chain_id' => '5',
                        'rpc_urls' => 'https://goerli.infura.io/v3/',
                        'block_explorer_urls' => 'https://goerli.etherscan.io/',
                        'chain_name' => 'Goerli Test Network',
                        'unit' => 'GoerliUSDT',
                        'contract_wallet' => '0xC2C527C0CACF457746Bd31B2a698Fe89de2b6d49',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 2,
                        'chain_id' => '5',
                        'rpc_urls' => 'https://goerli.infura.io/v3/',
                        'block_explorer_urls' => 'https://goerli.etherscan.io/',
                        'chain_name' => 'Goerli Test Network',
                        'unit' => 'GoerliETH',
                        'contract_wallet' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 3,
                        'chain_id' => '97',
                        'rpc_urls' => 'https://data-seed-prebsc-1-s1.binance.org:8545/',
                        'block_explorer_urls' => 'https://testnet.bscscan.com/',
                        'chain_name' => 'Binance Smart Chain Testnet',
                        'unit' => 'BNB',
                        'contract_wallet' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 4,
                        'chain_id' => '97',
                        'rpc_urls' => 'https://data-seed-prebsc-1-s1.binance.org:8545/',
                        'block_explorer_urls' => 'https://testnet.bscscan.com/',
                        'chain_name' => 'Binance Smart Chain Testnet',
                        'unit' => 'BUSD',
                        'contract_wallet' => '0xeD24FC36d5Ee211Ea25A80239Fb8C4Cfd80f12Ee',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                ];
            case 'production':
                return [
                    [
                        'id' => 1,
                        'chain_id' => '1',
                        'rpc_urls' => 'https://mainnet.infura.io/v3/',
                        'block_explorer_urls' => 'https://etherscan.io/',
                        'chain_name' => 'Ethereum Mainnet',
                        'unit' => 'USDT',
                        'contract_wallet' => '0xdac17f958d2ee523a2206206994597c13d831ec7',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 2,
                        'chain_id' => '1',
                        'rpc_urls' => 'https://mainnet.infura.io/v3/',
                        'block_explorer_urls' => 'https://etherscan.io/',
                        'chain_name' => 'Ethereum Mainnet',
                        'unit' => 'ETH',
                        'contract_wallet' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 3,
                        'chain_id' => '56',
                        'rpc_urls' => 'https://bsc-dataseed.binance.org/',
                        'block_explorer_urls' => 'https://bscscan.com/',
                        'chain_name' => 'Binance Smart Chain',
                        'unit' => 'BNB',
                        'contract_wallet' => null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => 4,
                        'chain_id' => '56',
                        'rpc_urls' => 'https://bsc-dataseed.binance.org/',
                        'block_explorer_urls' => 'https://bscscan.com/',
                        'chain_name' => 'Binance Smart Chain',
                        'unit' => 'BUSD',
                        'contract_wallet' => '0xe9e7cea3dedca5984780bafc599bd69add087d56',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],
                ];
        }
    }
}
