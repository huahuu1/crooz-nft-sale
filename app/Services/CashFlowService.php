<?php

namespace App\Services;

use App\Models\CashFlow;
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
    public function createCashFlow($userId, $tokenId, $amount, $transactionType, $txHash, $paymentMethod)
    {
        DB::beginTransaction();
        try {
            DB::table('cash_flows')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'amount' => $amount,
                'transaction_type' => $transactionType,
                'tx_hash' => $txHash,
                'payment_method' => $paymentMethod,
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
     * Create cash flow data
     *
     * @param $userId, $tokenId, $amount, $transactionType, $txHash
     */
    public function createCashFlowWithDate($userId, $tokenId, $amount, $transactionType, $txHash, $paymentMethod, $dateTime)
    {
        return CashFlow::firstOrCreate([
            'tx_hash' => $txHash,
        ], [
            'user_id' => $userId,
            'token_id' => $tokenId,
            'amount' => $amount,
            'transaction_type' => $transactionType,
            'payment_method' => $paymentMethod,
            'created_at' => $dateTime,
            'updated_at' => $dateTime,
        ]);
    }
}
