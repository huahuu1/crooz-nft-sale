<?php

namespace App\Services;

use App\Models\TransactionHistory;
use App\Models\TransactionRanking;
use App\Models\TransactionRawData;

class RankingService
{
    /**
     * Create Transaction Raw Data
     *
     * @param string $chain
     * @param string $txHash
     * @param string $from
     * @param string $to
     * @param string $token
     * @param string $value
     * @return App\Models\TransactionRawData|array
     */
    public function createTransactionRawData($chain, $txHash, $from, $to, $token, $value, $timestamp)
    {
        return TransactionRawData::create([
            'chain' => $chain,
            'tx_hash' => $txHash,
            'from' => $from,
            'to' => $to,
            'token' => $token,
            'value' => $value,
            'timestamp' => $timestamp,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }

    /**
     * Create Transaction History
     *
     * @param string $chain
     * @param string $txHash
     * @param string $from
     * @param string $to
     * @param string $token
     * @param string $value
     * @return App\Models\TransactionHistory|array
     */
    public function createTransactionHistory($chain, $txHash, $from, $to, $token, $value, $timestamp)
    {
        return TransactionHistory::create([
            'chain' => $chain,
            'tx_hash' => $txHash,
            'from' => $from,
            'to' => $to,
            'token' => $token,
            'value' => $value,
            'timestamp' => $timestamp,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }

    /**
     * Create Transaction Ranking
     *
     * @param string $walletAddress
     * @param string $txHash
     * @param string $amount
     * @return App\Models\TransactionRanking|array
     */
    public function createTransactionRanking($walletAddress, $txHash, $amount, $timestamp)
    {
        return TransactionRanking::create([
            'wallet_address' => $walletAddress,
            'tx_hash' => $txHash,
            'amount' => $amount,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }

    /**
     * Get Transaction Raw Data
     * @return App\Models\TransactionRawData|array
     */
    public function getTransactionRawData()
    {
        return TransactionRawData::select('id', 'chain', 'tx_hash', 'from', 'to', 'token', 'value', 'created_at')->get();
    }

    /**
     * Get Transaction Raw Data
     * @return App\Models\TransactionRawData|array
     */
    public function countTransactionRawData()
    {
        return TransactionRawData::select('id', 'chain', 'tx_hash', 'from', 'to', 'token', 'value', 'created_at')->count();
    }

    /**
     * Get Transaction History
     * @return App\Models\TransactionHistory|array
     */
    public function getTransactionHistory()
    {
        return TransactionHistory::select('id', 'chain', 'tx_hash', 'from', 'to', 'token', 'value')->get();
    }

    /**
     * Get Transaction History
     * @return App\Models\TransactionHistory|array
     */
    public function countTransactionHistory()
    {
        return TransactionHistory::select('id', 'chain', 'tx_hash', 'from', 'to', 'token', 'value')->count();
    }

    /**
     * Get Transactions Ranking
     * @return App\Models\TransactionRanking|array
     */
    public function getTransactionsRanking($numberRank, $startDate, $endDate, $amount)
    {
        return TransactionRanking::selectRaw('wallet_address, sum(amount) as amount, created_at')
            ->whereBetween('created_at', [(string)$startDate, (string)$endDate])
            ->groupBy('wallet_address')
            ->having('amount', '>=', $amount)
            ->orderBy('amount', 'DESC')
            ->take($numberRank)
            ->get();
    }


}
