<?php
return [
    'token_validate' => [
        'required' => 'The token validate field is required',
        'numeric' => 'The token validate must be a number',
        'digits' => 'The token validate must be 6 digits',
    ],
    'wallet_address' => [
        'required' => 'The wallet address field is required',
        'regex' => 'The wallet address format is invalid',
    ],
    'password' => [
        'required' => 'The password field is required',
        'min' => 'Please enter a password has at least 8 digits',
        'max' => 'Please enter a password has 16 digits or less',
        'regex' => 'The password format is invalid',
    ],
    'password_confirm' => 'The password confirm and password must match',
    'old_password' => [
        'required' => 'The old password field is required',
        'not_match' => 'The old password does not match',
    ],
    'email' => [
        'unique' => 'This email has already been used',
        'required' => 'The email field is required',
        'invalid' => 'The email format is invalid',
    ],
    'status' => [
        'required' => 'The status field is required',
    ],
    'amount' => [
        'required' => 'The amount field is required',
        'numeric' => 'The amount field must be number',
        'min' => 'The amount must be larger than 0',
    ],
    'tx_hash' => [
        'required' => 'The tx hash field is required',
    ],
    'token_id' => [
        'required' => 'The token id field is required',
    ],
    'signature' => [
        'required' => 'The required field is required'
    ]
];