<?php

namespace App\Jobs;

use App\Models\UserWithdrawal;
use App\Services\SaleInfoService;
use App\Services\UnlockBalanceHistoryService;
use App\Services\UserWithdrawalService;
use App\Traits\CalculateNextRunDate;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdatePrivateUnlockBalanceJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use CalculateNextRunDate;

    protected $unlockPrivateUserBalance;

    protected $userBalance;

    protected $unlockAmount;

    protected $saleInfoService;

    protected $unlockBalanceHistoryService;

    protected $userWithdrawalService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($unlockPrivateUserBalance, $userBalance, $unlockAmount = 0)
    {
        $this->unlockPrivateUserBalance = $unlockPrivateUserBalance;
        $this->userBalance = $userBalance;
        $this->unlockAmount = $unlockAmount;
        $this->saleInfoService = new SaleInfoService();
        $this->unlockBalanceHistoryService = new UnlockBalanceHistoryService();
        $this->userWithdrawalService = new UserWithdrawalService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //in case the last run of unlock
            if ($this->unlockPrivateUserBalance->amount_lock_remain < $this->unlockAmount) {
                //update unlock user balance then update the next run date
                $this->unlockPrivateUserBalance->amount_lock_remain -= $this->unlockPrivateUserBalance->amount_lock_remain;
                $this->unlockPrivateUserBalance->next_run_date = null;
                $this->unlockPrivateUserBalance->update();
                //update user balance
                $this->userBalance->amount_lock -= $this->userBalance->amount_lock;
                $this->userBalance->update();
            } else {
                //get info of token sale
                $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule(
                    $this->unlockPrivateUserBalance->token_sale_id
                );
                //the order of unlock rule
                $orderRun = $this->unlockPrivateUserBalance->current_order_unlock;
                //the next date to run unlock
                if ($this->unlockPrivateUserBalance->current_order_unlock < $tokenSaleInfo->token_unlock_rules->count()) {
                    $this->unlockPrivateUserBalance->next_run_date = $this->calculateNextRunDate(
                        $tokenSaleInfo->token_unlock_rules[$orderRun]->unit,
                        $tokenSaleInfo->token_unlock_rules[$orderRun]->period,
                        $this->unlockPrivateUserBalance->next_run_date
                    );
                }
                //update unlock user balance then update the next run date
                $this->unlockPrivateUserBalance->amount_lock_remain -= $this->unlockAmount;
                //case unlock 100%: update next_run_date = null after run
                if ((int) $this->unlockPrivateUserBalance->amount_lock == $this->unlockAmount) {
                    $this->unlockPrivateUserBalance->next_run_date = null;
                }
                //increase current_order_unlock after successful unlock
                $this->unlockPrivateUserBalance->current_order_unlock = $orderRun + 1;
                $this->unlockPrivateUserBalance->update();
                //update user balance
                $this->userBalance->amount_lock -= $this->unlockAmount;
                $this->userBalance->update();
            }
            //create unlock balance history data
            $this->unlockBalanceHistoryService->createPrivateUnlockBalanceHistory(
                $this->unlockPrivateUserBalance->id,
                $this->unlockAmount,
                Carbon::now()
            );
            //create user withdrawal data
            // $this->userWithdrawalService->createUserWithdrawal(
            //     $this->userBalance->user_id,
            //     $this->userBalance->token_id,
            //     $this->unlockAmount,
            //     Carbon::now(),
            //     UserWithdrawal::REQUESTING_STATUS
            // );
            //update amount total
            $this->userBalance->amount_total -= $this->unlockAmount;
            $this->userBalance->update();
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function createPrivateUnlockBalanceHistoryData($unlockId, $amount, $releaseTokenDate)
    {
        $this->unlockBalanceHistoryService->createPrivateUnlockBalanceHistory(
            $unlockId,
            $amount,
            $releaseTokenDate
        );
    }
}
