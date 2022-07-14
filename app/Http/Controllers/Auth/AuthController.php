<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
                'role_id' => $request->role_id,
                'mail' => $request->mail,
                'status' => $request->status,
                'password' => Hash::make($request['password']),
                'is_verified' => $request->is_verified,
            ]);

            return response()->json([
                'message' => 'User registered successfully.'
            ], 200);
        } catch (Exception $e) {
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
        try {
            $request->validate([
                'mail' => 'email|required',
                'password' => 'required'
            ]);

            $user = User::where('mail', $request->mail)->first();

            if (!$user || !Hash::check($request->password, $user->password, [])) {
                return response()->json([
                    'message' => 'The username or password is incorrect'
                ], 404);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (Exception $e) {
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
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
