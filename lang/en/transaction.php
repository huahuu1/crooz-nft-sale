<?php
return [
    'createDepositTokenTransaction' => [
        'duplicate' => 'This deposit transaction is duplicated',
        'connect_metamask' => 'Please connect to metamask',
        'success' => 'Deposit transaction successfully',
        'fail' => 'Deposit failed',
    ],
    'createDepositNftTransaction' => [
        'duplicate' => 'This deposit transaction is duplicated',
        'min_price' => 'The amount of :tokenName must be larger or equal to :minPrice',
        'connect_metamask' => 'Please connect to metamask',
        'success' => 'Deposit transaction successfully',
        'fail' => 'Deposit failed',
        'out_of_stock' => 'The package is out of stock',
        'out_day' => "Ticket can't exchange after sale finished.",
        'package_owned' => "You can purchase only one Hikaru Bundle per wallet address."
    ],
    'importUnlockUserBalance' => [
        'success' => 'Import unlock user balance successfully',
        'fail' => 'Import unlock user balance failed',
    ],
    'coupon' => [
        'fail' => 'unsuccessful use of discount code',
        'hasCoupon' => 'discount code does not exist',
        'success' => 'successfully used discount code'
    ]
];