<?php

namespace App\Jobs;

use App\Models\UnlockBalanceHistory;
use App\Services\SaleInfoService;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CalculateNextRunDate;

    protected $unlockUserBalance;

    protected $userBalance;

    protected $unlockAmount;

    protected $saleInfoService;

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

                UnlockBalanceHistory::create([
                    'unlock_id' => $this->unlockUserBalance->id,
                    'amount' => $this->unlockAmount,
                    'release_token_date' => Carbon::now(),
                ]);
            } else {
                //get info of token sale
                $tokenSaleInfo = $this->saleInfoService->getSaleInfo($this->unlockUserBalance->token_sale_id);
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

                UnlockBalanceHistory::create([
                    'unlock_id' => $this->unlockUserBalance->id,
                    'amount' => $this->unlockAmount,
                    'release_token_date' => Carbon::now(),
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
