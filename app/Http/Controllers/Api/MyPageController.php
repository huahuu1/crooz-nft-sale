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
use App\Services\UserNftService;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class MyPageController extends Controller
{
    protected $userBalanceService;
    protected $userService;
    protected $userWithdrawalService;
    protected $userNftService;

    /**
     * MyPageController constructor.
     *
     * @param use UserBalanceService $userBalanceService, UserService $userService, UserWithdrawalService $userWithdrawalService
     */
    public function __construct(UserBalanceService $userBalanceService,
                                UserService $userService,
                                UserWithdrawalService $userWithdrawalService,
                                UserNftService $userNftService)
    {
        $this->userBalanceService = $userBalanceService;
        $this->userService = $userService;
        $this->userWithdrawalService = $userWithdrawalService;
        $this->userNftService = $userNftService;
    }

    /**
     * Get history list
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistoryListByWalletAddress($walletAddress, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? env('MAX_PER_PAGE_MYPAGE');

        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $tokenSaleHistory = TokenSaleHistory::select(
                                                'token_sale_histories.*',
                                                'cash_flows.transaction_type as transaction_type'
                                            )
                                            ->with(['user', 'token_master'])
                                            ->join('cash_flows', 'token_sale_histories.tx_hash', '=', 'cash_flows.tx_hash')
                                            ->where('token_sale_histories.user_id', $user->id)
                                            ->get();

        $nftAuctionHistory = NftAuctionHistory::select(
                                                'nft_auction_histories.*',
                                                'cash_flows.transaction_type as transaction_type'
                                            )
                                            ->with(['user', 'token_master'])
                                            ->join('cash_flows', 'nft_auction_histories.tx_hash', '=', 'cash_flows.tx_hash')
                                            ->where('nft_auction_histories.user_id', $user->id)
                                            ->get();

        $result = collect($tokenSaleHistory)->merge(collect($nftAuctionHistory))->sortByDesc('created_at')->paginate($maxPerPage);

        return response()->json([
            'data' => $result->values()->all(),
            'total_pages' => $result->lastPage()
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
     * Get nfts of a user by wallet address
     *
     * @return \Illuminate\Http\Response
     */
    public function getNftByWalletAddress($walletAddress)
    {
        $user = $this->userService->getUserByWalletAddress($walletAddress);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $nfts = $this->userNftService->getUserNfts($user->id);
        return response()->json([
            'data' => $nfts
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
