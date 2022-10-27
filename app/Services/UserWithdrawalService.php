<?php

namespace App\Services;

use App\Models\UserWithdrawal;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserWithdrawalService
{
    /**
     * Get balances of a user follow token id
     *
     * @param $id
     * @return UserWithdrawal
     */
    public function getUserWithdrawalById($id)
    {
        return UserWithdrawal::where('id', $id)->first();
    }

    /**
     * Get user withdrawal requests with private unlock
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserWithdrawalsHasPrivateUnlock()
    {
        return UserWithdrawal::select(
            'id',
            'user_id',
            'token_id',
            'private_unlock_id',
            'amount',
            'request_time',
            'status',
            'note'
        )
        ->with(
            'privateUnlock:id,token_id,wallet_address,token_unlock_volume,unlock_date,status'
        )
        ->get();
    }

    /**
     * Get user withdrawal requests with private unlock history
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserWithdrawalsHasPrivateUnlockHistory()
    {
        return UserWithdrawal::select(
            'id',
            'user_id',
            'token_id',
            'private_unlock_id',
            'amount',
            'request_time',
            'status',
            'note'
        )
        ->where('status', UserWithdrawal::PROCESSING_STATUS)
        ->with(
            'privateUnlockHistory:id,unlock_id,amount,unlock_token_date,admin_id,network_id,tx_hash,status'
        )
        ->get();
    }

    /**
     * Get user withdrawal requests with private unlock history
     *
     * @param $id
     * @return \App\Models\UserWithdrawal
     */
    public function getUserWithdrawalByTxHash($txHash)
    {
        return UserWithdrawal::select(
            'user_withdrawals.id',
            'user_withdrawals.user_id',
            'user_withdrawals.token_id',
            'user_withdrawals.private_unlock_id',
            'user_withdrawals.amount',
            'user_withdrawals.request_time',
            'user_withdrawals.status',
            'user_withdrawals.note',
            'private_unlock_balance_histories.tx_hash'
        )
        ->join(
            'private_unlock_balance_histories',
            'private_unlock_balance_histories.unlock_id',
            '=',
            'user_withdrawals.private_unlock_id'
        )
        ->where('private_unlock_balance_histories.tx_hash', $txHash)
        ->first();
    }

    /**
     * Create user withdrawal data
     *
     * @param $userId, $tokenId, $amount, $requestTime, $status
     */
    public function createUserWithdrawal($userId, $tokenId, $privateUnlockId, $amount, $requestTime, $status)
    {
        DB::beginTransaction();
        try {
            DB::table('user_withdrawals')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'private_unlock_id' => $privateUnlockId,
                'amount' => $amount,
                'request_time' => $requestTime,
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
