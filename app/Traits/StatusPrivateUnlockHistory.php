<?php

namespace App\Traits;

use App\Models\PrivateUnlockBalanceHistory;

trait StatusPrivateUnlockHistory
{
    /**
     * @param $status
     * @return int
     */
    public function statusPrivateUnlockHistory($status)
    {
        switch ($status) {
            case 0:
                return PrivateUnlockBalanceHistory::PENDING_STATUS;
            case 1:
                return PrivateUnlockBalanceHistory::SUCCESS_STATUS;
            case -9:
                return PrivateUnlockBalanceHistory::FAIL_STATUS;
        }
    }
}
