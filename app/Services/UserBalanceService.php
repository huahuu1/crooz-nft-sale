<?php

namespace App\Services;

use App\Models\TokenMaster;
use App\Models\UserBalance;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserBalanceService
{
    /**
     * Get balances of a user follow token id
     *
     * @param $userId, $tokenId
     * @return \App\Models\UserBalance
     */
    public function getUserBalanceByTokenId($userId, $tokenId)
    {
        return UserBalance::select('id', 'user_id', 'token_id', 'amount_total', 'amount_lock')
                          ->where('user_id', $userId)
                          ->where('token_id', $tokenId)
                          ->first();
    }

    /**
     * Get balances of a user
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserBalances($userId)
    {
        return UserBalance::select('id', 'user_id', 'token_id', 'amount_total', 'amount_lock')
                          ->where('user_id', $userId)->where('token_id', UserBalance::GT)
                          ->with('tokenMaster:id,name,code,description,status')
                          ->get();
    }

    /**
     * Checking user has balance or not by user id.
     *
     * @param $userId
     * @return int
     */
    public function hasBalancesByUserId($userId)
    {
        return UserBalance::where('user_id', $userId)->count();
    }

    /**
     * Create user balance data
     *
     * @param $userId, $tokenId, $amountTotal, $amountLock
     */
    public function createUserBalance($userId, $tokenId, $amountTotal, $amountLock)
    {
        DB::beginTransaction();
        try {
            DB::table('user_balances')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'amount_total' => $amountTotal,
                'amount_lock' => $amountLock,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create user balance default data
     *
     * @param $userId
     */
    public function createDefaultUserBalance($userId)
    {
        DB::beginTransaction();
        try {
            $tokenList = TokenMaster::getTokenMasters();
            foreach ($tokenList as $token) {
                DB::table('user_balances')->insert([
                    'user_id' => $userId,
                    'token_id' => $token->id,
                    'amount_total' => 0,
                    'amount_lock' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
