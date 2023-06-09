<?php

return [
    'api' => [
        'bsc' => [
            'name' => 'BSC',
            'ids' => [56, 97],
            'url' => env('BSCSCAN_API_URL', 'https://api-testnet.bscscan.com/api'),
            'contract_wallet_usdt' => env('CONTRACT_WALLET_BUSD', '0x337610d27c682E347C9cD60BD4b3b107C9d34dDd'),
            'api_key' => env('BSCSCAN_API_KEY', 'G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 25),
        ],
        'eth' => [
            'name' => 'ETH',
            'ids' => [1, 5],
            'url' => env('ETHERSSCAN_API_URL', 'https://api-goerli.etherscan.io/api'),
            'contract_wallet_usdt' => env('CONTRACT_WALLET_USDT', '0x50c4c585912B0B9EB2E382a1a8c96bcA9b112441'),
            'api_key' => env('ETHERSCAN_API_KEY', 'ENBK5HBW1JFGY2INMUN28UDA88VM1Y6GJS'),
            'block_count' => env('SUCCESS_TRANSACTION_BNB_BLOCK_COUNT', 24),
        ],
    ],
    'wallet' => [
        'company_token_sale' => env('COMPANY_WALLET', '0x1B73dA81086D6BF763e0f3b7622740F335B04927'),
        'company_nft' => env('NFT_COMPANY_WALLET', '0x3e91A94484f04e7b8B5a0156e2373D4788F8Cc8d'),
    ],
    'exchange_rate' => [
        'cron' => env('EXCHANGE_RATES_CRON_VALUE', '*/30 * * * *'),
        'api_key' => env('EXCHANGE_RATES_API_KEY', 'm43yUcLasiNRTcOj8koulizfhAEKSF0A'),
        'url' => env('EXCHANGE_RATES_API_URL', 'https://api.apilayer.com/exchangerates_data/latest')
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
    'amount_ticket' => env('AMOUNT_TICKET', 200),
    'fincode_api_url' => env('FINCODE_API_URL', 'https://api.test.fincode.jp/v1'),
    'fincode_authorization_token' => env('FINCODE_AUTHORIZATION_TOKEN', 'm_test_NWViZWE0ODgtZmQ4NS00NjNiLWJhNWQtZTNiZjI4M2ZjNTBhZmIzMDZlMzYtZjdhNi00NmJkLWExMmEtODE2M2Y3M2E3OTc2c18yMjEwMzE3MDQxMA'),
    'gacha_api_url' => env('GACHA_API_URL', 'http://127.0.0.1:8001'),
    'number_of_ranking' => env('NUMBER_OF_RANKING', 10),
    'amount_of_ranking' => env('AMOUNT_OF_RANKING', 300),
    'cron_time_value' => [
        'ranking' => env('RANKING_CRON_VALUE', '*/30 * * * *'),
        'insert_nft_auction_history' => env('INSERT_NFT_AUCTION_HISTORY_CRON_VALUE', '*/10 * * * *')
    ],
    'mail_receive_failed_job' => env('MAIL_RECEIVE_FAILED_JOB', 'huuth@kozo-japan.com'),
    'ticket' => [
        'contract_wallet' => env('TICKET_CONTRACT_WALLET', '0x922Eea7c52Bf3Be99a851227D73d8F63b82e9422'),
        'destination_address' => env('TICKET_DESTINATION_ADDRESS', '0x9106C3CF4B1619EdDeDFfD91556C88507e71DE81'),
        'update_ticket_cron_time' => env('UPDATE_TICKET_CRON_TIME', '0 * * * *'),
    ],
    'coupon' => [
        'timeout' => env('TIMEOUT_HOLDING_COUPON', 3),
    ],
];
