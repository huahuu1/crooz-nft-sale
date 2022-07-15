<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use App\Models\EarningWallet;
use App\Models\User;
use Exception;

class TransactionController extends Controller
{
    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDepositTransaction(Request $request, $walletAddress, $txHash)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $depositTransaction = EarningWallet::where('tx_hash', $request->tx_hash)->first();

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated'
                ], 500);
            }

            $user = User::where('wallet_address', $walletAddress)->first();

            EarningWallet::create([
                "user_id" => $user->id,
                "token_id" => 1,
                "lock_time_id" => 1,
                "amount" => $request->amount,
                "type" => 'AUCTION',
                "status" => 'REQUESTING',
                "tx_hash" => $txHash,
            ]);

            return response()->json([
                'message' => 'Deposit transaction successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Deposit transaction failed',
                'error' => $e,
            ], 500);
        }
    }
}
