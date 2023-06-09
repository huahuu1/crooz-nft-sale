<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Models\UserWithdrawal;
use App\Services\HistoryListService;
use App\Services\UserBalanceService;
use App\Services\UserNftService;
use App\Services\UserService;
use App\Services\UserWithdrawalService;
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
    public function getHistoryListOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        $nftAuctionHistory = $this->historyListService->getNftAuctionHistories($user->id);

        $result = collect($nftAuctionHistory)
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
    public function getBalanceOfUser($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
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
    public function getNftOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        $nfts = $this->userNftService->getUserNfts($user->id, $maxPerPage);
        return response()->json([
            'data' => $nfts->values()->all(),
            'total_pages' => $nfts->lastPage()
        ]);
    }

    /**
     * Get nfts of a user by wallet address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNftOfUserByTypeId($user, $nftType, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        $nfts = $this->userNftService->getUserNftsByTypeId($user->wallet_address, $nftType, $maxPerPage);

        return response()->json([
            'data' => $nfts->values()->all(),
            'total_pages' => $nfts->lastPage()
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
                    'message' => __('user.getUser.not_found'),
                ], 404);
            }

            $userBalance = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id);

            $amountAvailable = $userBalance->amount_available;

            if ($request->amount > $amountAvailable) {
                return response()->json([
                    'message' => __('mypage.requestToWithdrawToken.max_amount'),
                ], 400);
            }

            //create user withdrawal data
            // $this->userWithdrawalService->createUserWithdrawal(
            //     $user->id,
            //     $request->token_id,
            //     $request->amount,
            //     Carbon::now(),
            //     UserWithdrawal::OPEN_STATUS
            // );

            //update amount total
            $userBalance->amount_total -= $request->amount;
            $userBalance->update();

            return response()->json([
                'message' => __('mypage.requestToWithdrawToken.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('mypage.requestToWithdrawToken.fail'),
                'error' => $e,
            ], 400);
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
                    'message' => __('mypage.updateStatusWithdrawRequest.not_found'),
                ], 404);
            }

            //update withdrawl reques status
            $userWithdrawal->status = $request->status;
            $userWithdrawal->update();

            //case reject withdrawl request: refund to user's balance
            //delete withdraw request after reject
            if ($request->status == UserWithdrawal::FAIL_STATUS) {
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId(
                    $userWithdrawal->user_id,
                    $userWithdrawal->token_id
                );

                $userBalance->amount_total += $userWithdrawal->amount;
                $userBalance->update();

                $userWithdrawal->where('id', $request->id)->delete();
            }

            return response()->json([
                'message' => __('mypage.updateStatusWithdrawRequest.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('mypage.updateStatusWithdrawRequest.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Count nfts group by type id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countNftGroupByTypeId($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);
        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }
        return response()->json([
            'data' => $this->userNftService->countNftGroupByTypeId($user->wallet_address),
        ]);
    }

    /**
     * Get user profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);
        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }
        return response()->json([
            'data' => $user,
        ]);
    }
}
