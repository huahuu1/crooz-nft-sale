<?php

namespace App\Traits;

use App\Models\UserWithdrawal;

trait StatusUserWithdrawal
{
    /**
     * @param $status
     * @return int
     */
    public function statusUserWithdrawal($status)
    {
        switch ($status) {
            case 0:
                return UserWithdrawal::PROCESSING_STATUS;
            case 1:
                return UserWithdrawal::SUCCESS_STATUS;
            case -9:
                return UserWithdrawal::FAIL_STATUS;
        }
    }
}
