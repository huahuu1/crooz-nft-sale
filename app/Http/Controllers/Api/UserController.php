<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'data' => User::all(),
        ]);
    }

    /**
     * Update email of user.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateEmailByWalletAddress(Request $request, $walletAddress)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $user = User::where('wallet_address', $walletAddress)->first();
            $user->mail = $request->email;
            $user->save();

            return response()->json([
                'message' => 'Update email successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Update email failed',
                'error' => $e,
            ], 500);
        }
    }
}
