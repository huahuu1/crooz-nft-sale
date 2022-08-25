<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Get balances of a user follow token id
     *
     * @param walletAddress
     * @return user
     */
    public function getUserByWalletAddress($walletAddress)
    {
        return User::where('wallet_address', $walletAddress)->first();
    }
}
