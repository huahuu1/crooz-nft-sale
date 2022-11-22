<?php

namespace App\Services;

use App\Models\NftAuctionHistory;
use App\Models\NftAuctionInfo;
use Exception;

class HistoryListService
{
    /**
     * Get history list of nft auction by user id
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNftAuctionHistories($userId)
    {
        return NftAuctionHistory::select(
            'nft_auction_histories.*',
            'cash_flows.transaction_type as transaction_type'
        )
            ->with(['user', 'tokenMaster'])
            ->join('cash_flows', 'nft_auction_histories.tx_hash', '=', 'cash_flows.tx_hash')
            ->where('nft_auction_histories.user_id', $userId)
            ->get();
    }

    /**
     * Get history list of nft auction by tx hash
     *
     * @param $txHash
     * @return NftAuctionHistory
     */
    public function getNftAuctionHistoryByTxHash($txHash)
    {
        return NftAuctionHistory::select('id', 'user_id', 'token_id', 'nft_auction_id', 'amount', 'status', 'tx_hash', 'package_id')
            ->with(['tokenMaster', 'networkMaster'])
            ->where('tx_hash', $txHash)
            ->first();
    }

    /**
     * Get success history list of nft auction by user id
     *
     * @param $maxPerPage
     * @return NftAuctionHistory
     */
    public function getSuccessNftAuctionHistoryByUserIdHasPagination($userId, $maxPerPage)
    {
        return NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
            ->where('user_id', $userId)
            ->orderby('created_at', 'desc')
            ->with(
                [
                    'user:id,email,wallet_address,token_validate,status',
                    'tokenMaster:id,name,code,description,status',
                    'nftAuctionInfo:id,name'
                ]
            )
            ->get()
            ->paginate($maxPerPage);
    }


    /**
     * Get pending success history list of nft auction by user id
     *
     * @param $maxPerPage
     * @return NftAuctionHistory
     */
    public function getPendingAndSuccessNftAuctionHistoryByUserIdHasPagination($userId, $maxPerPage)
    {
        return NftAuctionHistory::whereIn('status', [NftAuctionHistory::SUCCESS_STATUS, NftAuctionHistory::PENDING_STATUS])
            ->where('user_id', $userId)
            ->orderby('created_at', 'desc')
            ->with(
                [
                    'user:id,email,wallet_address,token_validate,status',
                    'tokenMaster:id,name,code,description,status',
                    'nftAuctionInfo:id,name'
                ]
            )
            ->get()
            ->paginate($maxPerPage);
    }

    /**
     * Get success history list of nft auction
     *
     * @param $maxPerPage
     * @return NftAuctionHistory
     */
    public function getSuccessNftAuctionHistoryHasPagination($maxPerPage)
    {
        return NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
            ->orderby('amount', 'desc')
            ->with([
                'user:id,email,wallet_address,token_validate,status',
                'tokenMaster:id,name,code,description,status'
            ])
            ->get()
            ->paginate($maxPerPage);
    }

    /**
     * Create nft auction history data
     *
     * @param $userId, $tokenId, $tokenSaleId, $amount, $status, $txHash
     * @return \App\Models\NftAuctionHistory | null
     */
    public function createNftAuctionHistory($userId, $tokenId, $nftAuctionId, $amount, $status, $txHash, $paymentMethod, $packageID)
    {
        try {
            $auctionHistory = NftAuctionHistory::create([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'nft_auction_id' => $nftAuctionId,
                'amount' => $amount,
                'status' => $status,
                'tx_hash' => $txHash,
                'payment_method' => $paymentMethod,
                'package_id' => $packageID,
            ]);
            return $auctionHistory;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            return null;
        }
    }
    /**
     * get Nft Auction Histories By Package
     *
     * @param int $auction_id
     * @param int $user_id
     * @param int $package_id
     * @return NftAuctionHistory
     */
    public function getNftAuctionHistoriesByPackage($userId, $packageId, $auctionId)
    {
        return NftAuctionHistory::where('user_id', $userId)
            ->where('nft_auction_id', $auctionId)
            ->where('package_id', $packageId)
            ->whereIn('status', [NftAuctionHistory::SUCCESS_STATUS, NftAuctionHistory::PENDING_STATUS])
            ->count();
    }
}
