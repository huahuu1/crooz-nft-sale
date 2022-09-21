<?php

namespace App\Jobs;

use App\Models\TokenMaster;
use App\Services\SaleInfoService;
use App\Services\UnlockUserBalanceService;
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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use CalculateNextRunDate;

    protected $userService;

    protected $userBalanceService;

    protected $transaction;

    protected $saleInfoService;

    protected $unlockUserBalanceService;

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
        $this->unlockUserBalanceService = new UnlockUserBalanceService();
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
            $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule($this->transaction->token_sale_id);
            //the next date to run unlock
            $nextRunDate = $this->calculateNextRunDate($tokenSaleInfo->token_unlock_rules[0]->unit, $tokenSaleInfo->token_unlock_rules[0]->period, $tokenSaleInfo->end_date);
            //exchange rate between tokens and GT
            $amountLock = $this->transaction->amount * $tokenSaleInfo->price;

            $this->unlockUserBalanceService->createUnlockUserBalance(TokenMaster::GT, $this->transaction->token_sale_id, $this->transaction->user_id, $amountLock, $amountLock, $nextRunDate);

            //check the user has email or not
            $email = $this->userService->hasVerifiedEmailByUserId($this->transaction->user_id);
            //if not email then create user balance, if has email then update it
            if (! $email) {
                $hasBalance = $this->userBalanceService->hasBalancesByUserId($this->transaction->user_id);
                if (! $hasBalance) {
                    $tokenList = TokenMaster::getTokenMasters();
                    foreach ($tokenList as $token) {
                        $this->userBalanceService->createUserBalance($this->transaction->user_id, $token->id, 0, 0);
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
