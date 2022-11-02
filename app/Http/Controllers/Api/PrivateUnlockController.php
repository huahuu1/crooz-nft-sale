<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\UserWithdrawal;
use App\Services\CashFlowService;
use App\Services\PrivateUnlockService;
use App\Services\UnlockBalanceHistoryService;
use App\Services\UserBalanceService;
use App\Services\UserService;
use App\Services\UserWithdrawalService;
use App\Traits\ApiPrivateUnlockToken;
use App\Traits\StatusPrivateUnlockHistory;
use App\Traits\StatusUserWithdrawal;
use Carbon\Carbon;
use Exception;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateUnlockController extends Controller
{
    use ApiPrivateUnlockToken;

    use StatusUserWithdrawal;

    use StatusPrivateUnlockHistory;

    protected $userService;

    protected $userWithdrawalService;

    protected $privateUnlockService;

    protected $unlockBalanceHistoryService;

    protected $cashFlowService;

    protected $userBalanceService;

    /**
     * UserController constructor.
     *
     * @param UserWithdrawalService $userWithdrawalService
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService,
        UserWithdrawalService $userWithdrawalService,
        PrivateUnlockService $privateUnlockService,
        UnlockBalanceHistoryService $unlockBalanceHistoryService,
        CashFlowService $cashFlowService,
        UserBalanceService $userBalanceService
    ) {
        $this->userWithdrawalService = $userWithdrawalService;
        $this->userService = $userService;
        $this->privateUnlockService = $privateUnlockService;
        $this->unlockBalanceHistoryService = $unlockBalanceHistoryService;
        $this->cashFlowService = $cashFlowService;
        $this->userBalanceService = $userBalanceService;
    }

    /**
     * Check status user withdrawal request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUnlockTokenUpToDate()
    {
        try {
            $userWithdrawals = $this->userWithdrawalService->getUserWithdrawalsHasPrivateUnlock();
            foreach ($userWithdrawals as $userWithdrawal) {
                //check if the token unlock date is up to date
                $currentDate = Carbon::createMidnightDate();
                $releaseDay = $currentDate->diffInDays($userWithdrawal->privateUnlock->unlock_date, false);
                if ($releaseDay <= 0 && $userWithdrawal->status == 'WAITING') {
                    $userWithdrawal->status = UserWithdrawal::OPEN_STATUS;
                    $userWithdrawal->save();
                }
            }

            return response()->json([
                'message' => __('privateUnlock.checkStatusUserWithdrawalRequest.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('privateUnlock.checkStatusUserWithdrawalRequest.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Approve user withdrawal request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveWithdrawalRequest(Request $request)
    {
        try {
            $apiKey = config('defines.api_key');
            $baseUri = config('defines.uri_unlock_token');
            //get user withdrawal request by id
            $userWithdrawal = $this->userWithdrawalService->getUserWithdrawalById($request->id);
            //prevent case input wrong id of user withdrawal
            if (!$userWithdrawal) {
                return response()->json([
                    'data' => 'Input wrong user withdrawal request',
                ], 400);
            }
            //get user by user withdrawal user id
            $user = $this->userService->getUserByWalletAddressOrByUserId($userWithdrawal->user_id);
            //get private user unlock data by by user withdrawal private unlock id
            $unlockInfo = $this->privateUnlockService->getPrivateUserUnlockBalanceById(
                $userWithdrawal->private_unlock_id
            );
            //call api token transfer
            $result = $this->tokenTransfers(
                $baseUri,
                $apiKey,
                $request->network_id,
                $user->wallet_address,
                $userWithdrawal->amount,
                $unlockInfo->token_type,
            );
            //case api token transfer return error (it's a string)
            if (gettype($result) == 'string') {
                return response()->json([
                    'data' => $result,
                ], 400);
            }
            //case has result status = 0 (pending)
            if (!is_null($result['status']) && !$result['status']) {
                //create data in private unlock history
                $this->unlockBalanceHistoryService->createPrivateUnlockBalanceHistory(
                    $userWithdrawal->private_unlock_id,
                    $userWithdrawal->amount,
                    Carbon::now(),
                    Auth::id(),
                    $request->network_id,
                    $result['txHash'],
                    $this->statusPrivateUnlockHistory($result['status'])
                );
                //update status of user withdrawal request
                $userWithdrawal->status = UserWithdrawal::PROCESSING_STATUS;
                $userWithdrawal->save();
            }

            return response()->json([
                'data' => $result,
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Check status of withdrawal request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatusWithdrawalRequests(Request $request)
    {
        try {
            $apiKey = config('defines.api_key');
            $baseUri = config('defines.uri_unlock_token');
            //get user withdrawal request by id
            $userWithdrawals = $this->userWithdrawalService->getUserWithdrawalsHasPrivateUnlockHistory();
            //create tx hashes array
            $txHashes = collect([]);

            foreach ($userWithdrawals as $userWithdrawal) {
                if (!empty($userWithdrawal->privateUnlockHistory->tx_hash)) {
                    $txHashes->push($userWithdrawal->privateUnlockHistory->tx_hash);
                }
            }
            //case tx hashes array is empty
            if ($txHashes->isEmpty()) {
                return response()->json([
                    'message' => 'Has nothing to check',
                ], 400);
            }
            //call api transaction statuses
            $results = $this->checkTransactionStatuses(
                $baseUri,
                $apiKey,
                $request->network_id,
                $txHashes
            );

            foreach ($results as $result) {
                //change status of user withdrawal request
                $userWithdrawal = $this->userWithdrawalService->getUserWithdrawalByTxHash($result['hash']);
                $userWithdrawal->status = $this->statusUserWithdrawal($result['status']);
                $userWithdrawal->save();
                //change status of private unlock history
                $unlockHistory = $this->unlockBalanceHistoryService->getPrivateUserUnlockHistoryById(
                    $userWithdrawal->private_unlock_id
                );
                $unlockHistory->status = $this->statusPrivateUnlockHistory($result['status']);
                $unlockHistory->save();
                //get user's balance
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId(
                    $userWithdrawal->user_id,
                    $userWithdrawal->token_id
                );
                //case status success then create data in Cash Flow and minus user's balance
                if ($result['status']) {
                    $this->cashFlowService->createCashFlow(
                        $userWithdrawal->user_id,
                        $userWithdrawal->token_id,
                        $userWithdrawal->amount,
                        CashFlow::TOKEN_WITHDRAWAL,
                        $unlockHistory->tx_hash
                    );
                    $userBalance->amount_total -= $userWithdrawal->amount;
                    $userBalance->save();
                }
            }

            return response()->json([
                'message' => $results,
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Change status fail',
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Get data of private user unlock balance
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrivateUserUnlockBalances()
    {
        $result = $this->privateUnlockService->getPrivateUserUnlockBalances();
        return response()->json([
            'data' => $result
        ]);
    }

    /**
     * Get data of user withdrawal
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserWithdrawalsHasPrivateUnlock()
    {
        $result = $this->userWithdrawalService->getUserWithdrawalsHasPrivateUnlock();
        return response()->json([
            'data' => $result
        ]);
    }

    /**
     * Get data of private unlock
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataPrivateUnlock(Request $request, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.admin');
        if (count($request->all()) <= 1) {
            return response()->json([
                'data' => [],
                'total_pages' => 1
            ]);
        }
        $result = $this->privateUnlockService->getDataPrivateUnlock($request->all(), $maxPerPage);
        return response()->json([
            'data' => $result->values()->all(),
            'total_pages' => $result->lastPage()
        ]);
    }
}
