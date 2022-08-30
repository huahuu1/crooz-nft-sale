<?php

namespace App\Services;

use App\Models\UserWithdrawal;

class UserWithdrawalService
{
    /**
     * Get balances of a user follow token id
     *
     * @param walletAddress
     * @return user
     */
    public function getUserWithdrawalById($id)
    {
        return UserWithdrawal::where('id', $id)->first();
    }
}
