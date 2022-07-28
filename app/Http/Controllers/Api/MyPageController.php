<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use App\Models\User;

class MyPageController extends Controller
{
    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingListOfTokenSaleWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $tokenSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::PENDING_STATUS)
                                            ->where('user_id', $user->id)
                                            ->orderby('id', 'asc')
                                            ->with('user')
                                            ->get();
        return response()->json([
            'data' => $tokenSaleHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuccessListOfTokenSaleByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $tokenSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::SUCCESS_STATUS)
                                            ->where('user_id', $user->id)
                                            ->orderby('id', 'asc')
                                            ->with('user')
                                            ->get();
        return response()->json([
            'data' => $tokenSaleHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingListOfNftAuctionByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::PENDING_STATUS)
                                              ->where('user_id', $user->id)
                                              ->orderby('id', 'asc')
                                              ->with('user')
                                              ->get();
        return response()->json([
            'data' => $nftAuctionHistory
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuccessListOfNftAuctionByWalletAddress($walletAddress)
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
                                              ->where('user_id', $user->id)
                                              ->orderby('id', 'asc')
                                              ->with('user')
                                              ->get();
        return response()->json([
            'data' => $nftAuctionHistory
        ]);
    }
}
