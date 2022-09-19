<?php

return [
    'api' => [
        'bsc' => [
            'name' => 'BSC',
            'url' => env('BSCSCAN_API_URL', 'https://api-testnet.bscscan.com/api'),
            'api_key' => env('BSCSCAN_API_KEY', 'G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 25)
        ],
        'eth' => [
            'name' => 'ETH',
            'url' => env('ETHERSSCAN_API_URL', 'https://api-ropsten.etherscan.io/api'),
            'api_key' => env('ETHERSCAN_API_KEY', 'ENBK5HBW1JFGY2INMUN28UDA88VM1Y6GJS'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 24)
        ]
    ],
    'wallet' => [
        'usdt' => env('CONTRACT_WALLET_USDT', '0x110a13fc3efe6a245b50102d2d79b3e76125ae83'),
        'company_token_sale' => env('COMPANY_WALLET', '0x1B73dA81086D6BF763e0f3b7622740F335B04927'),
        'company_nft' => env('NFT_COMPANY_WALLET', '0x3e91A94484f04e7b8B5a0156e2373D4788F8Cc8d'),
    ],
    'scan_api' => 'BSC'
];
