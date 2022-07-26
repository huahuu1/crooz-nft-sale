<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDepositTokenTransaction(TransactionRequest $request)
    {
        try {
            $depositTransaction = TokenSaleHistory::where('tx_hash', $request->tx_hash)->first();

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated'
                ], 500);
            }

            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Please connect to metamask'
                ], 500);
            }

            TokenSaleHistory::create([
                "user_id" => $user->id,
                "token_id" => 1,
                "token_sale_id" => 1,
                "amount" => $request->amount,
                "status" => 'PROCESSING',
                "tx_hash" => $request->tx_hash,
            ]);

            CashFlow::create([
                "user_id" => $user->id,
                "token_id" => 1,
                "amount" => $request->amount,
                "transaction_type" => 'TOKEN_DEPOSIT',
                "tx_hash" => $request->tx_hash,
            ]);

            return response()->json([
                'message' => 'Deposit transaction successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Deposit transaction failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDepositNftTransaction(TransactionRequest $request)
    {
        try {
            $depositTransaction = NftAuctionHistory::where('tx_hash', $request->tx_hash)->first();

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated'
                ], 500);
            }

            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Please connect to metamask'
                ], 500);
            }

            NftAuctionHistory::create([
                "user_id" => $user->id,
                "token_id" => 1,
                "nft_auction_id" => 1,
                "amount" => $request->amount,
                "status" => 'PROCESSING',
                "tx_hash" => $request->tx_hash,
            ]);

            CashFlow::create([
                "user_id" => $user->id,
                "token_id" => 1,
                "amount" => $request->amount,
                "transaction_type" => 'NFT_DEPOSIT',
                "tx_hash" => $request->tx_hash,
            ]);

            return response()->json([
                'message' => 'Deposit transaction successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Deposit transaction failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Get purchase list of token sale
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfTokenSale()
    {
        $tokeSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::SUCCESS_STATUS)
                                           ->orderby('amount', 'desc')
                                           ->with('user')
                                           ->get();
        return response()->json([
            'data' => $tokeSaleHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfNftAuction()
    {
        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
                                              ->orderby('amount', 'desc')
                                              ->with('user')
                                              ->get();
        return response()->json([
            'data' => $nftAuctionHistory
        ]);
    }
}
