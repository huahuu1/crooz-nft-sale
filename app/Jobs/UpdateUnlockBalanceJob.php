<?php

namespace App\Jobs;

use App\Services\SaleInfoService;
use App\Services\UnlockBalanceHistoryService;
use App\Traits\CalculateNextRunDate;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateUnlockBalanceJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use CalculateNextRunDate;

    protected $unlockUserBalance;

    protected $userBalance;

    protected $unlockAmount;

    protected $saleInfoService;

    protected $unlockBalanceHistoryService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($unlockUserBalance, $userBalance, $unlockAmount = 0)
    {
        $this->unlockUserBalance = $unlockUserBalance;
        $this->userBalance = $userBalance;
        $this->unlockAmount = $unlockAmount;
        $this->saleInfoService = new SaleInfoService();
        $this->unlockBalanceHistoryService = new UnlockBalanceHistoryService();
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
            if ($this->unlockUserBalance->amount_lock_remain < $this->unlockAmount) {
                //update unlock user balance then update the next run date
                $this->unlockUserBalance->amount_lock_remain -= $this->unlockUserBalance->amount_lock_remain;
                $this->unlockUserBalance->next_run_date = null;
                $this->unlockUserBalance->update();
                //update user balance
                $this->userBalance->amount_lock -= $this->userBalance->amount_lock;
                $this->userBalance->update();
                //create unlock balance history data
                $this->unlockBalanceHistoryService->createUnlockBalanceHistory($this->unlockUserBalance->id, $this->unlockAmount, Carbon::now());
            } else {
                //get info of token sale
                $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule($this->unlockUserBalance->token_sale_id);
                //the order of unlock rule
                $orderRun = $this->unlockUserBalance->current_order_unlock;
                //the next date to run unlock
                if ($this->unlockUserBalance->current_order_unlock < $tokenSaleInfo->token_unlock_rules->count()) {
                    $nextRunDate = $this->calculateNextRunDate($tokenSaleInfo->token_unlock_rules[$orderRun]->unit, $tokenSaleInfo->token_unlock_rules[$orderRun]->period, $this->unlockUserBalance->next_run_date);
                }
                //update unlock user balance then update the next run date
                $this->unlockUserBalance->amount_lock_remain -= $this->unlockAmount;
                $this->unlockUserBalance->next_run_date = $nextRunDate;
                //case unlock 100%: update next_run_date = null after run
                if ((int) $this->unlockUserBalance->amount_lock == $this->unlockAmount) {
                    $this->unlockUserBalance->next_run_date = null;
                }
                //increase current_order_unlock after successful unlock
                $this->unlockUserBalance->current_order_unlock = $orderRun + 1;
                $this->unlockUserBalance->update();
                //update user balance
                $this->userBalance->amount_lock -= $this->unlockAmount;
                $this->userBalance->update();
                //create unlock balance history data
                $this->unlockBalanceHistoryService->createUnlockBalanceHistory($this->unlockUserBalance->id, $this->unlockAmount, Carbon::now());
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
