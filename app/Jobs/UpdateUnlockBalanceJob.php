<?php

namespace App\Jobs;

use App\Models\TokenSaleInfo;
use App\Models\UnlockBalanceHistory;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $unlockUserBalance;

    protected $userBalance;

    protected $unlockAmount;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->unlockUserBalance->amount_lock_remain < $this->unlockAmount) {
                $this->unlockUserBalance->amount_lock_remain -= $this->unlockUserBalance->amount_lock_remain;

                $this->unlockUserBalance->next_run_date = null;
                $this->unlockUserBalance->update();

                $this->userBalance->amount_lock -= $this->userBalance->amount_lock;
                $this->userBalance->update();

                UnlockBalanceHistory::create([
                    'unlock_id' => $this->unlockUserBalance->id,
                    'amount' => $this->unlockAmount,
                    'release_token_date' => Carbon::now(),
                ]);
            } else {
                $tokenSaleInfo = TokenSaleInfo::select('rule_id', 'price', 'end_date')->where('id', $this->unlockUserBalance->token_sale_id)->with('token_unlock_rule:id,rule_code')->first();

                $tokenUnlockRule = collect($tokenSaleInfo->token_unlock_rule->all()[0]);

                $currentRunDate = new Carbon($this->unlockUserBalance->next_run_date);

                $orderRun = $this->unlockUserBalance->current_order_unlock;

                if ($orderRun < count($tokenUnlockRule['rule_code'])) {
                    switch ($tokenUnlockRule['rule_code'][$orderRun]['unit']) {
                        case 'DAY':
                            $nextRunDate = $currentRunDate->addDays($tokenUnlockRule['rule_code'][$orderRun]['period']);
                            break;
                        case 'MONTH':
                            $nextRunDate = $currentRunDate->addMonths($tokenUnlockRule['rule_code'][$orderRun]['period']);
                            break;
                        case 'YEAR':
                            $nextRunDate = $currentRunDate->addYears($tokenUnlockRule['rule_code'][$orderRun]['period']);
                            break;
                    }
                }

                $this->unlockUserBalance->amount_lock_remain -= $this->unlockAmount;
                $this->unlockUserBalance->next_run_date = $nextRunDate;

                //case vesting update next_run_date = null after run
                if ((int) $this->unlockUserBalance->amount_lock == $this->unlockAmount) {
                    $this->unlockUserBalance->next_run_date = null;
                }

                $this->unlockUserBalance->current_order_unlock = $orderRun + 1;
                $this->unlockUserBalance->update();

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
