<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\WalletAddressRequest;
use App\Models\User;
use App\Notifications\EmailAuthenticationNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        try {
            User::create([
                'email' => $request->mail,
                'status' => $request->status,
                'password' => Hash::make($request['password']),
            ]);

            return response()->json([
                'message' => 'User registered successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $user = User::where('email', $request->mail)->first();

            if (!$user || !Hash::check($request->password, $user->password, [])) {
                return response()->json([
                    'message' => 'The username or password is incorrect'
                ], 404);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Error in Login',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        // Revoke all tokens
        try {
            Auth::user()->tokens()->delete();
            return response()->json([
                'message' => 'Logout'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => $e
            ], 500);
        }
    }

    /**
     * Register by Wallet Address api
     *
     * @return \Illuminate\Http\Response
     */
    public function registerByWalletAddress(WalletAddressRequest $request)
    {
        try {
            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (!$user) {
                User::create([
                    'wallet_address' => $request->wallet_address
                ]);

                $user = User::where('wallet_address', $request->wallet_address)->first();

                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'message' => 'User registered successfully.',
                    'data' => $user,
                    'access_token' => $token
                ], 200);
            }

            return response()->json([
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Send token to mail
     *
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request) {
        $request->validate([
            'mail' => 'required|email'
        ]);
        try {
            $user = User::where('mail', $request->mail)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Email does not exist'
                ], 200);
            }

            $tokenValidate = random_int(100000, 999999);

            $user->token_validate = $tokenValidate;

            $user->save();

            $emailAuthenticationNotification = new EmailAuthenticationNotification($request->mail, $tokenValidate);

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
     * Check token of user input is correct or not
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmToken(ConfirmTokenRequest $request) {
        try {
            $user = User::where('wallet_address', $request->wallet_address)->first();

            if (Carbon::parse($user->updated_at)->addMinutes(1)->isPast()) {
                $user->token_validate = null;
                $user->save();
                return response()->json([
                    'message' => 'Token has expired'
                ], 200);
            }

            if ($user->token_validate != $request->token_validate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verify email token wrong'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Verify email token successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => $e,
            ], 500);
        }
    }
}
