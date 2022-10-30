<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Get user by wallet address or by user id
     *
     * @param $value
     * @return User
     */
    public function getUserByWalletAddressOrByUserId($value)
    {
        return User::select('id', 'email', 'wallet_address', 'token_validate', 'status')
                   ->where('wallet_address', $value)
                   ->orWhere('id', $value)
                   ->first();
    }

    /**
     * Get user by wallet address
     *
     * @param $walletAddress
     * @return User
     */
    public function getUserByWalletAddress($walletAddress)
    {
        return User::select(
            'id',
            'email',
            'wallet_address',
            'token_validate',
            'status'
        )
        ->with('gxePartnerUser:id,user_id')
        ->where('wallet_address', $walletAddress)
        ->first();
    }

    /**
     * Get user by email
     *
     * @param $walletAddress
     * @return User
     */
    public function getUserByEmail($email)
    {
        return User::select('id', 'email', 'wallet_address', 'token_validate', 'status')
                   ->where('email', $email)
                   ->first();
    }

    /**
     * Checking user has email or not by wallet address.
     *
     * @param $walletAddress
     * @return int
     */
    public function hasVerifiedEmailByWalletAddress($walletAddress)
    {
        return User::select('email')
            ->where('wallet_address', $walletAddress)
            ->whereNotNull('email')
            ->count();
    }

    /**
     * Checking user has email or not by user id.
     *
     * @param $userId
     * @return int
     */
    public function hasVerifiedEmailByUserId($userId)
    {
        return User::select('email')->where('id', $userId)
            ->whereNotNull('email')
            ->count();
    }
}
