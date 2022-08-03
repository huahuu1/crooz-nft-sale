<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\UserWithdrawal;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class MyPageController extends Controller
{
    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingListOfTokenSaleWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $tokenSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::PENDING_STATUS)
                                            ->where('user_id', $user->id)
                                            ->orderby('id', 'desc')
                                            ->with('user')
                                            ->get();
        return response()->json([
            'data' => $tokenSaleHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuccessListOfTokenSaleByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $tokenSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::SUCCESS_STATUS)
                                            ->where('user_id', $user->id)
                                            ->orderby('id', 'desc')
                                            ->with('user')
                                            ->get();
        return response()->json([
            'data' => $tokenSaleHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingListOfNftAuctionByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::PENDING_STATUS)
                                              ->where('user_id', $user->id)
                                              ->orderby('id', 'desc')
                                              ->with('user')
                                              ->get();
        return response()->json([
            'data' => $nftAuctionHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuccessListOfNftAuctionByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
                                              ->where('user_id', $user->id)
                                              ->orderby('id', 'desc')
                                              ->with('user')
                                              ->get();
        return response()->json([
            'data' => $nftAuctionHistory
        ]);
    }

    /**
     * Get balances of a user by wallet address
     *
     * @return \Illuminate\Http\Response
     */
    public function getBalanceByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $balances = UserBalance::where('user_id', $user->id)
                               ->with('token_master')
                               ->get();
        return response()->json([
            'data' => $balances
        ]);
    }

    /**
     * Get balances of a user by wallet address
     * @param walletAddress
     * @param tokenId
     * @return balance
     */
    public function getUserBalance($walletAddress, $tokenId)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $balance = UserBalance::where('user_id', $user->id)
                               ->where('token_id', $tokenId)
                               ->first();
        return $balance;
    }

    /**
     * User requests to withdrawl token
     *
     * @return \Illuminate\Http\Response
     */
    public function requestToWithdrawToken(WithdrawRequest $request)
    {
        try {
            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $userBalance = $this->getUserBalance($request->wallet_address, $request->token_id);

            $amountAvailable = $userBalance->amount_available;

            if ($request->amount > $amountAvailable) {
                return response()->json([
                    'message' => 'The amount must be smaller than or equal to the available amount'
                ], 500);
            }

            UserWithdrawal::create([
                "user_id" => $user->id,
                "token_id" => $request->token_id,
                "amount" => $request->amount,
                "request_time" => Carbon::now(),
                "status" => UserWithdrawal::REQUESTING_STATUS,
            ]);

            //update amount total
            $userBalance->amount_total -= $request->amount;
            $userBalance->save();

            return response()->json([
                'message' => 'Withdraw request successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Withdraw request failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Update status of user_withdrawals
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatusWithdrawRequest(Request $request)
    {
        try {
            $userWithdrawal = UserWithdrawal::where('id', $request->id)->first();

            if (!$userWithdrawal) {
                return response()->json([
                    'message' => 'Withdraw request not found'
                ], 404);
            }

            //update amount total
            $userWithdrawal->status = $request->status;
            $userWithdrawal->save();

            return response()->json([
                'message' => 'Change status successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Change status failed',
                'error' => $e,
            ], 500);
        }
    }
}
