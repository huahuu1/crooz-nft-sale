<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserBalanceRequest;
use App\Http\Requests\UserRequest;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Services\UserBalanceService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Str;

class UserController extends Controller
{
    protected $userService;

    protected $userBalanceService;

    /**
     * UserController constructor.
     *
     * @param UserBalanceService $userBalanceService
     * @param UserService $userService
     */
    public function __construct(
        UserService $userService,
        UserBalanceService $userBalanceService
    ) {
        $this->userBalanceService = $userBalanceService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'data' => User::select('id', 'email', 'wallet_address', 'token_validate', 'status')->get(),
        ]);
    }

    /**
     * Update email of user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmailByWalletAddress(UserRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => __('user.getUser.not_found'),
                ], 404);
            }

            $user->email = $request->email;
            $user->token_validate = null;
            $user->save();

            return response()->json([
                'data' => $user,
                'message' => __('user.updateEmailByWalletAddress.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('user.updateEmailByWalletAddress.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * create default balance of user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDefaultBalanceByWalletAddress(UserBalanceRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (! $user) {
                return response()->json([
                    'message' => __('user.getUser.not_found'),
                ], 404);
            }

            $userBalance = $this->userBalanceService->hasBalancesByUserId($user->id);

            if (! $userBalance) {
                $this->userBalanceService->createDefaultUserBalance($user->id);
            }

            return response()->json([
                'message' => __('user.createDefaultBalanceByWalletAddress.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('user.createDefaultBalanceByWalletAddress.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Checking user has email or not by wallet address.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmailUserByWalletAddress($walletAddress)
    {
        try {
            $email = $this->userService->hasVerifiedEmailByWalletAddress($walletAddress);

            return response()->json([
                'data' => $email ? true : false,
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Send email to reset password
     *
     * @return \Illuminate\Http\JsonResponse
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
                    'message' => __('user.getUser.not_found'),
                ], 404);
            }

            $passwordReset = PasswordReset::updateOrCreate(
                [
                    'email' => $request->email,
                    'token' => Str::random(60),
                ]
            );

            //Send email contains token to user
            $emailAuthenticationNotification = new ResetPasswordNotification(
                $passwordReset->email,
                $passwordReset->token
            );

            $user->notify($emailAuthenticationNotification);

            return response()->json([
                'success' => true,
                'message' => __('user.sendEmailResetPassword.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('user.sendEmailResetPassword.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Reset the password of user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request, $token)
    {
        try {
            $passwordReset = PasswordReset::where('token', $token)->first();

            if (!$passwordReset) {
                return response()->json([
                    'message' => __('user.resetPassword.token_invalid'),
                ], 422);
            }

            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();

                return response()->json([
                    'message' => __('user.resetPassword.token_invalid'),
                ], 422);
            }

            $user = $this->userService->getUserByEmail($passwordReset->email);
            $user->password = Hash::make($request->password);
            $user->save();
            $passwordReset->delete();

            return response()->json([
                'success' => true,
                'message' => __('user.resetPassword.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('user.resetPassword.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Change the password of user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request, $user)
    {
        try {
            $user = $this->userService->getUserByWalletAddressOrByUserId($user);
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => __('user.changePassword.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('user.changePassword.fail'),
                'error' => $e,
            ], 400);
        }
    }
}
