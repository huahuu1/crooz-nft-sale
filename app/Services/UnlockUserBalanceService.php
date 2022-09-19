<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UnlockUserBalanceService
{
    /**
     * Create user unlock balance data
     *
     * @param $tokenId, $tokenSaleId, $userId, $amountLock, $amountLockRemain, $nextRunDate
     */
    public function createUnlockUserBalance($tokenId, $tokenSaleId, $userId, $amountLock, $amountLockRemain, $nextRunDate)
    {
        DB::beginTransaction();
        try {
            DB::table('user_unlock_balances')->insert([
                'token_id' => $tokenId,
                'token_sale_id' => $tokenSaleId,
                'user_id' => $userId,
                'amount_lock' => $amountLock,
                'amount_lock_remain' => $amountLockRemain,
                'next_run_date' => $nextRunDate,
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