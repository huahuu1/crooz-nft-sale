<?php

namespace App\Services;

use App\Models\PrivateUnlockBalanceHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UnlockBalanceHistoryService
{
    /**
     * Create private unlock balance history data
     *
     * @param $unlockId, $amount, $releaseTokenDate
     */
    public function createPrivateUnlockBalanceHistory(
        $unlockId,
        $amount,
        $unlockTokenDate,
        $adminId,
        $networkId,
        $txHash,
        $status
    ) {
        DB::beginTransaction();
        try {
            DB::table('private_unlock_balance_histories')->insert([
                'unlock_id' => $unlockId,
                'amount' => $amount,
                'unlock_token_date' => $unlockTokenDate,
                'admin_id' => $adminId,
                'network_id' => $networkId,
                'tx_hash' => $txHash,
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

    /**
     * get private user unlock balance history by unlock id
     *
     */
    public function getPrivateUserUnlockHistoryById($unlockId)
    {
        return PrivateUnlockBalanceHistory::select(
            'id',
            'unlock_id',
            'amount',
            'unlock_token_date',
            'admin_id',
            'network_id',
            'tx_hash',
            'status'
        )
        ->where('unlock_id', $unlockId)
        ->with('admin:id,email')
        ->first();
    }
}
