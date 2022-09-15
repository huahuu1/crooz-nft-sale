<?php

namespace App\Services;

use App\Models\UserBalance;

class UserBalanceService
{
    /**
     * Get balances of a user follow token id
     *
     * @param walletAddress
     * @param tokenId
     * @return balance
     */
    public function getUserBalanceByTokenId($userId, $tokenId)
    {
        return UserBalance::where('user_id', $userId)
                          ->where('token_id', $tokenId)
                          ->first();
    }

    /**
     * Get balances of a user
     *
     * @param walletAddress
     * @return balances
     */
    public function getUserBalances($userId)
    {
        return UserBalance::where('user_id', $userId)
                          ->where('token_id', UserBalance::GT)
                          ->with('token_master')
                          ->get();
    }

    /**
     * Checking user has balance or not by user id.
     *
     * @param walletAddress
     * @return balances
     */
    public function hasBalancesByUserId($userId)
    {
        return UserBalance::where('user_id', $userId)->count();
    }
}
