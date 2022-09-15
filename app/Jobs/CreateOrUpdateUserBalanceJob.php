<?php

namespace App\Jobs;

use App\Models\TokenMaster;
use App\Models\UnlockUserBalance;
use App\Models\UserBalance;
use App\Services\SaleInfoService;
use App\Services\UserBalanceService;
use App\Services\UserService;
use App\Traits\CalculateNextRunDate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateUserBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CalculateNextRunDate;

    protected $userService;

    protected $userBalanceService;

    protected $transaction;

    protected $saleInfoService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->userService = new UserService();
        $this->userBalanceService = new UserBalanceService();
        $this->saleInfoService = new SaleInfoService();
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
            //get info of token sale
            $tokenSaleInfo = $this->saleInfoService->getSaleInfo($this->transaction->token_sale_id);
            //the next date to run unlock
            $nextRunDate = $this->calculateNextRunDate($tokenSaleInfo->token_unlock_rules[0]->unit, $tokenSaleInfo->token_unlock_rules[0]->period, $tokenSaleInfo->end_date);
            //exchange rate between tokens and GT
            $amountLock = $this->transaction->amount * $tokenSaleInfo->price;

            UnlockUserBalance::create([
                'token_id' => TokenMaster::GT,
                'token_sale_id' => $this->transaction->token_sale_id,
                'user_id' => $this->transaction->user_id,
                'amount_lock' => $amountLock,
                'amount_lock_remain' => $amountLock,
                'next_run_date' => $nextRunDate,
            ]);

            //check the user has email or not
            $email = $this->userService->hasVerifiedEmailByUserId($this->transaction->user_id);
            //if not email then create user balance, if has email then update it
            if (! $email) {
                $hasBalance = $this->userBalanceService->hasBalancesByUserId($this->transaction->user_id);
                if (! $hasBalance) {
                    $tokenList = TokenMaster::all();
                    foreach ($tokenList as $token) {
                        UserBalance::create([
                            'user_id' => $this->transaction->user_id,
                            'token_id' => $token->id,
                            'amount_total' => 0,
                            'amount_lock' => 0,
                        ]);
                    }
                }
            }
            $userBalance = $this->userBalanceService->getUserBalances($this->transaction->user_id)[0];
            $userBalance->amount_total += $amountLock;
            $userBalance->amount_lock += $amountLock;
            $userBalance->update();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
