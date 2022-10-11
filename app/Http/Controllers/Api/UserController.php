<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserBalanceRequest;
use App\Http\Requests\UserRequest;
use App\Models\TokenMaster;
use App\Models\User;
use App\Services\UserBalanceService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Log;

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
                    'message' => 'User not found',
                ], 404);
            }

            $user->email = $request->email;
            $user->token_validate = null;
            $user->save();

            return response()->json([
                'data' => $user,
                'message' => 'Update email successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Update email failed',
                'error' => $e,
            ], 500);
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
                    'message' => 'User not found',
                ], 404);
            }

            $userBalance = $this->userBalanceService->hasBalancesByUserId($user->id);

            if (! $userBalance) {
                $this->userBalanceService->createDefaultUserBalance($user->id);
            }

            return response()->json([
                'message' => 'Create default balance successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Create default balance failed',
                'error' => $e,
            ], 500);
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
            ], 500);
        }
    }
}
