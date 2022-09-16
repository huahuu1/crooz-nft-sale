<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CashFlowService
{
    /**
     * Create cash flow data
     *
     * @param $userId, $tokenId, $amount, $transactionType, $txHash
     */
    public function createCashFlow($userId, $tokenId, $amount, $transactionType, $txHash)
    {
        DB::beginTransaction();
        try {
            DB::table('cash_flows')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'amount' => $amount,
                'transaction_type' => $transactionType,
                'tx_hash' => $txHash,
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
