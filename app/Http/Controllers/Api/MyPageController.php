<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SwapTokenRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\UserWithdrawal;
use App\Services\HistoryListService;
use App\Services\UserBalanceService;
use App\Services\UserNftService;
use App\Services\UserService;
use App\Services\UserWithdrawalService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MyPageController extends Controller
{
    protected $userBalanceService;

    protected $userService;

    protected $userWithdrawalService;

    protected $userNftService;

    protected $historyListService;

    /**
     * MyPageController constructor.
     *
     * @param UserBalanceService $userBalanceService
     * @param UserService $userService
     * @param UserWithdrawalService $userWithdrawalService
     */
    public function __construct(
        UserBalanceService $userBalanceService,
        UserService $userService,
        UserWithdrawalService $userWithdrawalService,
        UserNftService $userNftService,
        HistoryListService $historyListService
    ) {
        $this->userBalanceService = $userBalanceService;
        $this->userService = $userService;
        $this->userWithdrawalService = $userWithdrawalService;
        $this->userNftService = $userNftService;
        $this->historyListService = $historyListService;
    }

    /**
     * Get history list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoryListByWalletAddress($walletAddress, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $tokenSaleHistory = $this->historyListService->getTokenSaleHistories($user->id);

        $nftAuctionHistory = $this->historyListService->getNftAuctionHistories($user->id);

        $result = collect($tokenSaleHistory)
                ->merge(collect($nftAuctionHistory))
                ->sortByDesc('created_at')
                ->paginate($maxPerPage);

        return response()->json([
            'data' => $result->values()->all(),
            'total_pages' => $result->lastPage(),
        ]);
    }

    /**
     * Get balances of a user by wallet address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalanceByWalletAddress($walletAddress)
    {
        $user = $this->userService->getUserByWalletAddress($walletAddress);
        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $balances = $this->userBalanceService->getUserBalances($user->id);

        return response()->json([
            'data' => $balances,
        ]);
    }

    /**
     * Get nfts of a user by wallet address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNftByWalletAddress($walletAddress)
    {
        $user = $this->userService->getUserByWalletAddress($walletAddress);
        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        $nfts = $this->userNftService->getUserNfts($user->id);

        return response()->json([
            'data' => $nfts,
        ]);
    }

    /**
     * User requests to withdrawl token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestToWithdrawToken(WithdrawRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (! $user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $userBalance = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id);

            $amountAvailable = $userBalance->amount_available;

            if ($request->amount > $amountAvailable) {
                return response()->json([
                    'message' => 'The amount must be smaller than or equal to the available amount',
                ], 500);
            }

            //create user withdrawal data
            $this->userWithdrawalService->createUserWithdrawal(
                $user->id,
                $request->token_id,
                $request->amount,
                Carbon::now(),
                UserWithdrawal::REQUESTING_STATUS
            );

            //update amount total
            $userBalance->amount_total -= $request->amount;
            $userBalance->update();

            return response()->json([
                'message' => 'Withdraw request successfully',
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusWithdrawRequest(Request $request)
    {
        try {
            $userWithdrawal = $this->userWithdrawalService->getUserWithdrawalById($request->id);

            if (! $userWithdrawal) {
                return response()->json([
                    'message' => 'Withdraw request not found',
                ], 404);
            }

            //update withdrawl reques status
            $userWithdrawal->status = $request->status;
            $userWithdrawal->update();

            //case reject withdrawl request: refund to user's balance
            //delete withdraw request after reject
            if ($request->status == UserWithdrawal::REJECT_STATUS) {
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId(
                    $userWithdrawal->user_id,
                    $userWithdrawal->token_id
                );

                $userBalance->amount_total += $userWithdrawal->amount;
                $userBalance->update();

                $userWithdrawal->where('id', $request->id)->delete();
            }

            return response()->json([
                'message' => 'Change status successfully',
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestToSwapToken(SwapTokenRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (! $user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $userBalanceTokenFrom = $this->userBalanceService->getUserBalanceByTokenId(
                $user->id,
                $request->token_id_from
            );
            $userBalanceTokenTo = $this->userBalanceService->getUserBalanceByTokenId(
                $user->id,
                $request->token_id_to
            );

            $amountAvailableFrom = $userBalanceTokenFrom->amount_available;

            if ($request->amount > $amountAvailableFrom) {
                return response()->json([
                    'message' => 'The amount must be smaller than or equal to the available amount',
                ], 500);
            }

            $userBalanceTokenFrom->amount_total -= $request->amount;
            $userBalanceTokenTo->amount_total += $request->amount;

            $userBalanceTokenFrom->update();
            $userBalanceTokenTo->update();

            return response()->json([
                'message' => 'Swap token successfully',
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
