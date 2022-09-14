<?php

namespace App\Jobs;

use App\Models\TokenMaster;
use App\Models\TokenSaleInfo;
use App\Models\UnlockUserBalance;
use App\Models\UserBalance;
use App\Services\UserBalanceService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateUserBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userService;

    protected $userBalanceService;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->userService = new UserService();
        $this->userBalanceService = new UserBalanceService();
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $tokenSaleInfo = TokenSaleInfo::select('rule_id', 'price', 'end_date')->where('id', $this->transaction->token_sale_id)->with('token_unlock_rule:id,rule_code')->first();

            $tokenUnlockRule = collect($tokenSaleInfo->token_unlock_rule->all()[0]);

            $amountLock = $this->transaction->amount * $tokenSaleInfo->price;
            $amountLockRemain = $amountLock;

            $endDate = new Carbon($tokenSaleInfo->end_date);

            switch ($tokenUnlockRule['rule_code'][0]['unit']) {
                case 'DAY':
                    $nextRunDate = $endDate->addDays($tokenUnlockRule['rule_code'][0]['period']);
                    break;
                case 'MONTH':
                    $nextRunDate = $endDate->addMonths($tokenUnlockRule['rule_code'][0]['period']);
                    break;
                case 'YEAR':
                    $nextRunDate = $endDate->addYears($tokenUnlockRule['rule_code'][0]['period']);
                    break;
            }

            UnlockUserBalance::create([
                'token_id' => TokenMaster::GT,
                'token_sale_id' => $this->transaction->token_sale_id,
                'user_id' => $this->transaction->user_id,
                'amount_lock' => $amountLock,
                'amount_lock_remain' => $amountLockRemain,
                'next_run_date' => $nextRunDate,
            ]);

            $email = $this->userService->hasVerifiedEmailByUserId($this->transaction->user_id);
            if (! $email) {
                $tokenList = TokenMaster::all();
                foreach ($tokenList as $token) {
                    UserBalance::create([
                        'user_id' => $this->transaction->user_id,
                        'token_id' => $token->id,
                        'amount_total' => 0,
                        'amount_lock' => 0,
                    ]);
                }
            } else {
                $userBalance = $this->userBalanceService->getUserBalances($this->transaction->user_id)[0];
                $userBalance->amount_total += $amountLock;
                $userBalance->amount_lock += $amountLock;
                $userBalance->update();
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
