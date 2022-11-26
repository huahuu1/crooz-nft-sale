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
    public function createTransactionRawData($chain, $txHash, $from, $to, $token, $value)
    {
        return TransactionRawData::create([
            'chain' => $chain,
            'tx_hash' => $txHash,
            'from' => $from,
            'to' => $to,
            'token' => $token,
            'value' => $value,
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
    public function createTransactionHistory($chain, $txHash, $from, $to, $token, $value)
    {
        return TransactionHistory::create([
            'chain' => $chain,
            'tx_hash' => $txHash,
            'from' => $from,
            'to' => $to,
            'token' => $token,
            'value' => $value,
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
    public function createTransactionRanking($walletAddress, $txHash, $amount)
    {
        return TransactionRanking::create([
            'wallet_address' => $walletAddress,
            'tx_hash' => $txHash,
            'amount' => $amount,
        ]);
    }
}
