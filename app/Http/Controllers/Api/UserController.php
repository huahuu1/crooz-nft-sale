<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletAddressRequest;
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
            $user = User::select('mail')->where('wallet_address', $walletAddress)
                                    ->whereNotNull('mail')
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
