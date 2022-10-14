<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SwapTokenRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\PasswordReset;
use App\Models\UserWithdrawal;
use App\Notifications\ResetPasswordNotification;
use App\Services\HistoryListService;
use App\Services\UserBalanceService;
use App\Services\UserNftService;
use App\Services\UserService;
use App\Services\UserWithdrawalService;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Str;

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
     * @param use UserBalanceService $userBalanceService, UserService $userService, UserWithdrawalService $userWithdrawalService
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
     * @return \Illuminate\Http\Response
     */
    public function getHistoryListOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $tokenSaleHistory = $this->historyListService->getTokenSaleHistories($user->id);

        $nftAuctionHistory = $this->historyListService->getNftAuctionHistories($user->id);

        $result = collect($tokenSaleHistory)->merge(collect($nftAuctionHistory))->sortByDesc('created_at')->paginate($maxPerPage);

        return response()->json([
            'data' => $result->values()->all(),
            'total_pages' => $result->lastPage(),
        ]);
    }

    /**
     * Get balances of a user by wallet address
     *
     * @return \Illuminate\Http\Response
     */
    public function getBalanceOfUser($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

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
     * @return \Illuminate\Http\Response
     */
    public function getNftOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
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
     * @return \Illuminate\Http\Response
     */
    public function getNftOfUserByTypeId($user, $typeId, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.my_page');

        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $nfts = $this->userNftService->getUserNftsByTypeId($user->id, $typeId, $maxPerPage);

        return response()->json([
            'data' => $nfts->values()->all(),
            'total_pages' => $nfts->lastPage()
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
            $this->userWithdrawalService->createUserWithdrawal($user->id, $request->token_id, $request->amount, Carbon::now(), UserWithdrawal::REQUESTING_STATUS);

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
     * @return \Illuminate\Http\Response
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
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId($userWithdrawal->user_id, $userWithdrawal->token_id);

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
     * @return \Illuminate\Http\Response
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

            $userBalanceTokenFrom = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id_from);
            $userBalanceTokenTo = $this->userBalanceService->getUserBalanceByTokenId($user->id, $request->token_id_to);

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

    /**
     * Count nfts group by type id
     *
     * @return Nft
     */
    public function countNftGroupByTypeId($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);
        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'data' => $this->userNftService->countNftGroupByTypeId($user->id)->all(),
        ]);
    }

    /**
     * Get user profile
     *
     * @return $user
     */
    public function getUserProfile($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);
        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Send email to reset password
     *
     * @return \Illuminate\Http\Response
     */
    public function sendEmailResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        try {
            $user = $this->userService->getUserByEmail($request->email);

            if (! $user) {
                return response()->json([
                    'message' => 'User does not exist',
                ], 404);
            }

            $passwordReset = PasswordReset::updateOrCreate(
                [
                    'email' => $request->email,
                    'token' => Str::random(60),
                ]
            );

            //Send email contains token to user
            $emailAuthenticationNotification = new ResetPasswordNotification($passwordReset->email, $passwordReset->token);

            $user->notify($emailAuthenticationNotification);

            return response()->json([
                'success' => true,
                'message' => 'Send email successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Send email failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Change the password of user
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ResetPasswordRequest $request, $token)
    {
        try {
            $passwordReset = PasswordReset::where('token', $token)->first();

            if (!$passwordReset) {
                return response()->json([
                    'message' => 'This password reset token is invalid',
                ], 422);
            }

            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();

                return response()->json([
                    'message' => 'This password reset token is invalid',
                ], 422);
            }

            $user = $this->userService->getUserByEmail($passwordReset->email);
            $user->password = Hash::make($request->password);
            $user->save();
            $passwordReset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reset password successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Reset password failed',
                'error' => $e,
            ], 500);
        }
    }
}
