<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UnlockBalanceHistoryService
{
    /**
     * Create unlock balance history data
     *
     * @param $unlockId, $amount, $releaseTokenDate
     */
    public function createUnlockBalanceHistory($unlockId, $amount, $releaseTokenDate)
    {
        DB::beginTransaction();
        try {
            DB::table('unlock_balance_histories')->insert([
                'unlock_id' => $unlockId,
                'amount' => $amount,
                'release_token_date' => $releaseTokenDate,
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
