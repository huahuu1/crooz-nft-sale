<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\WalletAddressRequest;
use App\Models\User;
use App\Notifications\EmailAuthenticationNotification;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userService;

    /**
     * AuthController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            User::create([
                'email' => $request->email,
                'status' => $request->status,
                'password' => Hash::make($request['password']),
            ]);

            return response()->json([
                'message' => __('authCustom.register.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('authCustom.register.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password, [])) {
                return response()->json([
                    'message' => __('authCustom.login.login_invalid'),
                ], 404);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('authCustom.login.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke all tokens
        try {
            $id = $request->user()->currentAccessToken()->id;
            Auth::user()->tokens()->where('id', $id)->delete();
            return response()->json([
                'message' => __('authCustom.logout.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Register by Wallet Address api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerByWalletAddress(WalletAddressRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            if (! $user) {
                User::create([
                    'wallet_address' => $request->wallet_address,
                ]);

                $user = $this->userService->getUserByWalletAddress($request->wallet_address);

                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'message' => __('authCustom.register.success'),
                    'data' => $user,
                    'access_token' => $token,
                    'exit_token' => Carbon::now('UTC')->addHours(3)
                ], 200);
            }

            return response()->json([
                'data' => $user,
                'access_token' => $user->createToken('authToken')->plainTextToken,
                'exit_token' => Carbon::now('UTC')->addHours(3)
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('authCustom.register.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Send token to mail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToken(VerifyEmailRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => __('user.getUser.not_found'),
                ], 404);
            }

            $checkDuplicateEmail = User::where('email', $request->email)->count();

            if ($checkDuplicateEmail > 0) {
                return response()->json([
                    'message' => __('user.email.duplicate'),
                ], 400);
            }

            //Token is random 6 digits
            $tokenValidate = random_int(100000, 999999);

            $user->token_validate = $tokenValidate;

            $user->save();

            //Send email contains token to user
            $emailAuthenticationNotification = new EmailAuthenticationNotification($request->email, $tokenValidate);

            $user->notify($emailAuthenticationNotification);

            return response()->json([
                'success' => true,
                'message' => __('authCustom.sendToken.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('authCustom.sendToken.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Check token of user input is correct or not
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmToken(ConfirmTokenRequest $request)
    {
        try {
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            //Token's duration is 10 minutes
            if (Carbon::parse($user->updated_at)->addMinutes(10)->isPast()) {
                $user->token_validate = null;
                $user->save();

                return response()->json([
                    'message' => __('authCustom.confirmToken.token_expired'),
                ], 404);
            }

            //User input wrong token
            if ($user->token_validate != $request->token_validate) {
                return response()->json([
                    'success' => false,
                    'message' => __('authCustom.confirmToken.wrong_input'),
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => __('authCustom.confirmToken.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('authCustom.confirmToken.fail'),
                'error' => $e,
            ], 400);
        }
    }
}
