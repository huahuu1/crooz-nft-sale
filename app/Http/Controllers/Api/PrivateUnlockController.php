<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserWithdrawal;
use App\Services\UnlockUserBalanceService;
use App\Services\UserService;
use App\Services\UserWithdrawalService;
use Carbon\Carbon;
use Exception;
use Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PrivateUnlockController extends Controller
{
    protected $userService;

    protected $userWithdrawalService;

    protected $unlockUserBalanceService;

    /**
     * UserController constructor.
     *
     * @param UserWithdrawalService $userWithdrawalService
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService,
        UserWithdrawalService $userWithdrawalService,
        UnlockUserBalanceService $unlockUserBalanceService
    ) {
        $this->userWithdrawalService = $userWithdrawalService;
        $this->userService = $userService;
        $this->unlockUserBalanceService = $unlockUserBalanceService;
    }

    /**
     * Check status user withdrawal request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatusUserWithdrawalRequest()
    {
        try {
            $userWithdrawals = $this->userWithdrawalService->getUserWithdrawalByIdHasPrivateUnlock();
            foreach ($userWithdrawals as $userWithdrawal) {
                //check if the token unlock date is up to date
                $currentDate = Carbon::createMidnightDate();
                $releaseDay = $currentDate->diffInDays($userWithdrawal->privateUnlock->unlock_date, false);
                if ($releaseDay <= 0) {
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
     * Get data of private user unlock balance
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrivateUserUnlockBalances()
    {
        $result = $this->unlockUserBalanceService->getPrivateUserUnlockBalances();
        return response()->json([
            'data' => $result
        ]);
    }

    /**
     * Get data of user withdrawal
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserWithdrawals()
    {
        $result = $this->userWithdrawalService->getUserWithdrawalByIdHasPrivateUnlock();
        return response()->json([
            'data' => $result
        ]);
    }
}
