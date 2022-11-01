<?php

namespace App\Services;

use App\Models\PrivateUserUnlockBalance;
use App\Models\TokenMaster;
use App\Models\UserWithdrawal;

class PrivateUnlockService
{
    /**
     * get all private user unlock balance data
     *
     */
    public function getPrivateUserUnlockBalances()
    {
        return PrivateUserUnlockBalance::select(
            'id',
            'token_id',
            'token_type',
            'wallet_address',
            'token_unlock_volume',
            'unlock_date',
            'status'
        )
        ->with('tokenMaster:id,name,code')
        ->get();
    }

    /**
     * get private user unlock balance by id
     *
     */
    public function getPrivateUserUnlockBalanceById($id)
    {
        return PrivateUserUnlockBalance::select(
            'id',
            'token_id',
            'token_type',
            'wallet_address',
            'token_unlock_volume',
            'unlock_date',
            'status'
        )
        ->where('id', $id)
        ->with('tokenMaster:id,name,code')
        ->first();
    }

    /**
     * get data of private unlock
     *
     */
    public function getDataPrivateUnlock($params)
    {
        return UserWithdrawal::select(
            'user_withdrawals.id',
            'user_balances.amount_total',
            'user_withdrawals.user_id',
            'user_withdrawals.request_time as grant_date',
            'private_user_unlock_balances.unlock_date',
            'private_user_unlock_balances.token_unlock_volume',
            'user_withdrawals.status',
            'private_user_unlock_balances.wallet_address',
            'private_user_unlock_balances.investor_classification',
            'private_user_unlock_balances.investor_name',
            'private_unlock_balance_histories.tx_hash',
        )
        ->leftJoin(
            'private_unlock_balance_histories',
            'private_unlock_balance_histories.unlock_id',
            '=',
            'user_withdrawals.private_unlock_id'
        )
        ->leftJoin(
            'private_user_unlock_balances',
            'private_user_unlock_balances.id',
            '=',
            'user_withdrawals.private_unlock_id'
        )
        ->leftJoin('user_balances', 'user_balances.user_id', '=', 'user_withdrawals.user_id')
        ->where('user_balances.token_id', TokenMaster::GT)
        ->orderBy('private_user_unlock_balances.unlock_date', 'ASC')
        ->when(!empty($params['unlock_date']), function ($q) use ($params) {
            $q->whereDate('private_user_unlock_balances.unlock_date', $params['unlock_date']);
        })
        ->when(!empty($params['status']), function ($q) use ($params) {
            $q->where('user_withdrawals.status', $params['status']);
        })
        ->when(!empty($params['wallet_address']), function ($q) use ($params) {
            $keyword = '%' . $params['wallet_address'] . '%';
            $q->where('private_user_unlock_balances.wallet_address', 'like', $keyword);
        })
        ->get();
    }
}
