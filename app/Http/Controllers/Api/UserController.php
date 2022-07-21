<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function updateEmailByWalletAddress(UserRequest $request)
    {
        try {
            $user = User::where('wallet_address', $request->wallet_address)->first();
            $user->email = $request->email;
            $user->save();

            return response()->json([
                'message' => 'Update email successfully'
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
     * Checking user has email or not by wallet address.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkEmailUserByWalletAddress($walletAddress)
    {
        try {
            $user = User::select('email')->where('wallet_address', $walletAddress)
                                         ->whereNotNull('email')
                                         ->count();
            return response()->json([
                'data' => $user ? true : false
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'error' => $e,
            ], 500);
        }

    }
}
