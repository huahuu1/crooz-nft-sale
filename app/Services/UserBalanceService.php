<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBalance;

class UserBalanceService
{
    protected $userService;

    /**
     * UserBalanceService constructor.
     *
     * @param use UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get balances of a user follow token id
     * @param walletAddress
     * @param tokenId
     * @return balance
     */
    public function getUserBalanceByTokenId($userId, $tokenId)
    {
        $balance = UserBalance::where('user_id', $userId)
                              ->where('token_id', $tokenId)
                              ->first();
        return $balance;
    }

    /**
     * Get balances of a user
     * @param walletAddress
     * @return balances
     */
    public function getUserBalances($userId)
    {
        $balances = UserBalance::where('user_id', $userId)
                               ->where('token_id', UserBalance::GT)
                               ->with('token_master')
                               ->get();
        return $balances;
    }
}
