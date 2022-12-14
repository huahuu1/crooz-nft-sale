<?php
return [
    'createDepositTokenTransaction' => [
        'duplicate' => 'This deposit transaction is duplicated',
        'connect_metamask' => 'Please connect to metamask',
        'success' => '入金成功しました。',
        'fail' => '入金失敗しました。',
    ],
    'createDepositNftTransaction' => [
        'duplicate' => 'This deposit transaction is duplicated',
        'min_price' => 'The amount of :tokenName must be larger or equal to :minPrice',
        'connect_metamask' => 'Please connect to metamask',
        'success' => '入金成功しました。',
        'fail' => '入金失敗しました。',
        'out_of_stock' => 'パッケージは在庫切れです',
        'out_day' => "Ticket can't exchange after sale finished.",
        'package_owned' => "ヒカルパックのご購入に使用される1ウォレットアドレスにつき1個まで購入できます"
    ],
    'importUnlockUserBalance' => [
        'success' => 'アンロック ユーザー残高のインポートに成功しました。',
        'fail' => 'アンロック ユーザー残高のインポートに失敗しました。',
    ],
    'coupon' => [
        'fail' => 'unsuccessful use of discount code',
        'hasCoupon' => 'discount code does not exist',
        'success' => 'successfully used discount code'
    ]
];