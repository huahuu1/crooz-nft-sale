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
     * Get balances of a user follow token id
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserWithdrawalByIdHasPrivateUnlock()
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
