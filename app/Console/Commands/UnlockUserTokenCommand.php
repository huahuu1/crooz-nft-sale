<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUnlockBalanceJob;
use App\Models\UnlockUserBalance;
use App\Services\SaleInfoService;
use App\Services\UserBalanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UnlockUserTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:user-balance';

    protected $unlockUserBalance;

    protected $userBalanceService;

    protected $saleInfoService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock user balance command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->unlockUserBalance = new UnlockUserBalance();
        $this->userBalanceService = new UserBalanceService();
        $this->saleInfoService = new SaleInfoService();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->tokenUnlockExecution();
    }

    /**
     * Validate Metamask Transaction
     *
     * @return void
     */
    public function tokenUnlockExecution()
    {
        $unlockUserBalanceList = $this->unlockUserBalance->getUnlockUserBalances();
        $unlockUserBalanceList->chunkById(100, function ($unlockUserBalances) {
            foreach ($unlockUserBalances as $key => $unlockUserBalance) {
                $userBalance = $this->userBalanceService->getUserBalanceByTokenId($unlockUserBalance->user_id, $unlockUserBalance->token_id);

                $checkDate = $this->checkReleaseDate($unlockUserBalance->next_run_date, $unlockUserBalance->updated_at);

                // update status = 0 with case amount_lock_remain < unlockAmount or amount_lock_remain = 0
                // this will update after a day the last run
                if ($unlockUserBalance->amount_lock_remain == 0) {
                    $unlockUserBalance->status = 0;
                    $unlockUserBalance->next_run_date = null;
                    $unlockUserBalance->update();
                }

                if ($checkDate) {
                    //get info of token sale
                    $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule($unlockUserBalance->token_sale_id);
                    //the order of unlock rule
                    $orderRun = $unlockUserBalance->current_order_unlock;
                    //calculate the amount to unlock
                    if ($orderRun < $tokenSaleInfo->token_unlock_rules->count()) {
                        $unlockAmount = $unlockUserBalance->amount_lock * $tokenSaleInfo->token_unlock_rules[$orderRun]->unlock_percentages / 100;
                    }
                    //unlock when status is not 0
                    if ($unlockUserBalance->status != 0) {
                        UpdateUnlockBalanceJob::dispatch($unlockUserBalance, $userBalance, $unlockAmount ? $unlockAmount : 0)->delay(now()->addSeconds(($key + 1) * 3));

                        Log::info('[SUCCESS] Unlock token for user ID: '.$unlockUserBalance->user_id.' - sale token ID: '.$unlockUserBalance->token_sale_id);
                        $this->info('[SUCCESS] Unlock token for user ID: '.$unlockUserBalance->user_id.' - sale token ID: '.$unlockUserBalance->token_sale_id);
                    }
                }
            }
        }, 'id');
    }

    /**
     * Check token release date
     *
     * @param  mixed  $endDate, $lockDay
     * @return bool
     */
    public function checkReleaseDate($nextRunDate, $updatedAt)
    {
        $today = Carbon::now();

        //the date must satisfy 3 conditions
        //the current day must equal or larger than the next_run_date
        //the number of day between the current day and updated_at must larger than 0
        return ($today->eq($nextRunDate) || $today->gt($nextRunDate)) && $today->diffInDays($updatedAt);
    }
}