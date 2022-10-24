<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserWithdrawal;
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

    /**
     * UserController constructor.
     *
     * @param UserWithdrawalService $userWithdrawalService
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService,
        UserWithdrawalService $userWithdrawalService
    ) {
        $this->userWithdrawalService = $userWithdrawalService;
        $this->userService = $userService;
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
}
