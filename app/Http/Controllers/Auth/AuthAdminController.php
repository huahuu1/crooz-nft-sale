<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $admin = Admin::where('email', $request->email)->first();

            if (! $admin || ! Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'The username or password is incorrect',
                ], 404);
            }

            $token = Auth::guard('admin')->user()->createToken('authAminToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'data' => $admin,
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
    public function logout()
    {
        Log::alert('logout');
        // Revoke all tokens
        try {
            Auth::guard('admin')->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'error' => $e,
            ], 500);
        }
    }
}
