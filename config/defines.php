<?php

return [
    'api' => [
        'bsc' => [
            'name' => 'BSC',
            'url' => env('BSCSCAN_API_URL', 'https://api-testnet.bscscan.com/api'),
            'contract_wallet_usdt' => env('CONTRACT_WALLET_BUSD', '0xeD24FC36d5Ee211Ea25A80239Fb8C4Cfd80f12Ee'),
            'api_key' => env('BSCSCAN_API_KEY', 'G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 25),
        ],
        'eth' => [
            'name' => 'ETH',
            'url' => env('ETHERSSCAN_API_URL', 'https://api-goerli.etherscan.io/api'),
            'contract_wallet_usdt' => env('CONTRACT_WALLET_USDT', '0xc2c527c0cacf457746bd31b2a698fe89de2b6d49'),
            'api_key' => env('ETHERSCAN_API_KEY', 'ENBK5HBW1JFGY2INMUN28UDA88VM1Y6GJS'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 24),
        ],
    ],
    'wallet' => [
        'company_token_sale' => env('COMPANY_WALLET', '0x1B73dA81086D6BF763e0f3b7622740F335B04927'),
        'company_nft' => env('NFT_COMPANY_WALLET', '0x3e91A94484f04e7b8B5a0156e2373D4788F8Cc8d'),
    ],
    'network' => env('NETWORK_BLOCKCHAIN', 'ETHERS'),
    'uri_unlock_token' => env('URI_UNLOCK_TOKEN', 'http://ec2-54-150-255-124.ap-northeast-1.compute.amazonaws.com:3000'),
    'pagination' => [
        'my_page' => env('MAX_PER_PAGE_MYPAGE', 100),
        'admin' => env('MAX_PER_PAGE_ADMIN', 10),
        'nft_auction' => env('MAX_PER_PAGE_AUCTION', 100),
        'token_sale' => env('MAX_PER_PAGE_TOKENSALE', 10),
    ],
    'queue' => [
        'general' => 'general',
        'check_status' => 'checkStatus',
    ],
    'password_decrypte' => env('PASSWORD_DECRYPTE', 'XENOPROJECT'),
    'language_default' => 'en',
    'api_key' => env('API_KEY', '314199DC-D666-465F-A8F9-781842380900'),
    'amount_ticket' => env('AMOUNT_TICKET', 200)
];
