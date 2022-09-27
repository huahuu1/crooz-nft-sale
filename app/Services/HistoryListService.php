<?php

namespace App\Services;

use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class HistoryListService
{
    /**
     * Get history list of token sale by user id
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTokenSaleHistories($userId)
    {
        return TokenSaleHistory::select([
            'token_sale_histories.*',
            'cash_flows.transaction_type as transaction_type'
        ])
            ->with(['user', 'token_master'])
            ->join('cash_flows', 'token_sale_histories.tx_hash', '=', 'cash_flows.tx_hash')
            ->where('token_sale_histories.user_id', $userId)
            ->get();
    }

    /**
     * Get history list of token sale by user id
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
            ->with(['user', 'token_master'])
            ->join('cash_flows', 'nft_auction_histories.tx_hash', '=', 'cash_flows.tx_hash')
            ->where('nft_auction_histories.user_id', $userId)
            ->get();
    }

    /**
     * Get history list of token sale by tx hash
     *
     * @param $txHash
     * @return TokenSaleHistory
     */
    public function getTokenSaleHistoryByTxHash($txHash)
    {
        return TokenSaleHistory::select('id', 'user_id', 'token_id', 'token_sale_id', 'amount', 'status', 'tx_hash')
            ->where('tx_hash', $txHash)->first();
    }

    /**
     * Get history list of nft auction by tx hash
     *
     * @param $txHash
     * @return NftAuctionHistory
     */
    public function getNftAuctionHistoryByTxHash($txHash)
    {
        return NftAuctionHistory::select('id', 'user_id', 'token_id', 'nft_auction_id', 'amount', 'status', 'tx_hash')
            ->where('tx_hash', $txHash)->first();
    }

    /**
     * Get success history list of token sale by user id
     *
     * @param $userId, $maxPerPage
     * @return NftAuctionHistory
     */
    public function getSuccessTokenSaleHistoryByUserIdHasPagination($userId, $maxPerPage)
    {
        return TokenSaleHistory::where('status', TokenSaleHistory::SUCCESS_STATUS)
            ->where('user_id', $userId)
            ->orderby('amount', 'desc')
            ->with([
                'user:id,email,wallet_address,token_validate,status',
                'token_master:id,name,code,description,status'
            ])
            ->get()
            ->paginate($maxPerPage);
    }

    /**
     * Get success history list of nft auction by user id
     *
     * @param $maxPerPage
     * @return NftAuctionHistory
     */
    public function getSuccessNftAuctionHistoryByUserIdHasPagination($maxPerPage)
    {
        return NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
            ->orderby('amount', 'desc')
            ->with([
                'user:id,email,wallet_address,token_validate,status',
                'token_master:id,name,code,description,status'
            ])
            ->get()
            ->paginate($maxPerPage);
    }

    /**
     * Create token sale history data
     *
     * @param $userId, $tokenId, $tokenSaleId, $amount, $status, $txHash
     */
    public function createTokenSaleHistory($userId, $tokenId, $tokenSaleId, $amount, $status, $txHash)
    {
        DB::beginTransaction();
        try {
            DB::table('token_sale_histories')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'token_sale_id' => $tokenSaleId,
                'amount' => $amount,
                'status' => $status,
                'tx_hash' => $txHash,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create nft auction history data
     *
     * @param $userId, $tokenId, $tokenSaleId, $amount, $status, $txHash
     */
    public function createNftAuctionHistory($userId, $tokenId, $nftAuctionId, $amount, $status, $txHash)
    {
        DB::beginTransaction();
        try {
            DB::table('nft_auction_histories')->insert([
                'user_id' => $userId,
                'token_id' => $tokenId,
                'nft_auction_id' => $nftAuctionId,
                'amount' => $amount,
                'status' => $status,
                'tx_hash' => $txHash,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
