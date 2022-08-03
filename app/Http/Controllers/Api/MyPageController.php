<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SwapTokenRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use App\Models\UserWithdrawal;
use App\Models\User;
use App\Services\UserWithdrawalService;
use App\Services\UserBalanceService;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;


class MyPageController extends Controller
{
    protected $userBalanceService;
    protected $userService;
    protected $userWithdrawalService;

    /**
     * MyPageController constructor.
     *
     * @param use UserBalanceService $userBalanceService
     */
    public function __construct(UserBalanceService $userBalanceService,
                                UserService $userService,
                                UserWithdrawalService $userWithdrawalService)
    {
        $this->userBalanceService = $userBalanceService;
        $this->userService = $userService;
        $this->userWithdrawalService = $userWithdrawalService;
    }

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
        $user = $this->userService->getUserByWalletAddress($walletAddress);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $balances = $this->userBalanceService->getUserBalances($user->id);
        return response()->json([
            'data' => $balances
        ]);
    }

    /**
     * User requests to withdrawl token
     *
     * @return \Illuminate\Http\Response
     */
    public function requestToWithdrawToken(WithdrawRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $userBalance = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id);

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
            $userWithdrawal = $this->userWithdrawalService->getUserWithdrawalById($request->id);

            if (!$userWithdrawal) {
                return response()->json([
                    'message' => 'Withdraw request not found'
                ], 404);
            }

            //update withdrawl reques status
            $userWithdrawal->status = $request->status;
            $userWithdrawal->save();

            //case reject withdrawl request: refund to user's balance
            //delete withdraw request after reject
            if ($request->status == UserWithdrawal::REJECT_STATUS) {
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId($userWithdrawal->user_id, $userWithdrawal->token_id);

                $userBalance->amount_total += $userWithdrawal->amount;
                $userBalance->save();

                $userWithdrawal->where('id', $request->id)->delete();
            }

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

    /**
     * Swap token in user balance
     *
     * @return \Illuminate\Http\Response
     */
    public function requestToSwapToken(SwapTokenRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $userBalanceTokenFrom = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id_from);
            $userBalanceTokenTo = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id_to);

            $amountAvailableFrom = $userBalanceTokenFrom->amount_available;

            if ($request->amount > $amountAvailableFrom) {
                return response()->json([
                    'message' => 'The amount must be smaller than or equal to the available amount'
                ], 500);
            }

            $userBalanceTokenFrom->amount_total -= $request->amount;
            $userBalanceTokenTo->amount_total += $request->amount;

            $userBalanceTokenFrom->save();
            $userBalanceTokenTo->save();

            return response()->json([
                'message' => 'Swap token successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Swap token failed',
                'error' => $e,
            ], 500);
        }
    }
}
