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

    /**
     * Checking user has email or not by wallet address.
     *
     * @return \Illuminate\Http\Response
     */
    public function hasVerifiedEmailByWalletAddress($walletAddress)
    {
        return User::select('email')->where('wallet_address', $walletAddress)
                                    ->whereNotNull('email')
                                    ->count();
    }

    /**
     * Checking user has email or not by user id.
     *
     * @return \Illuminate\Http\Response
     */
    public function hasVerifiedEmailByUserId($userId)
    {
        return User::select('email')->where('id', $userId)
                                    ->whereNotNull('email')
                                    ->count();
    }
}
