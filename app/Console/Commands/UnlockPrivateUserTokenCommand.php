<?php

namespace App\Console\Commands;

use App\Jobs\UpdatePrivateUnlockBalanceJob;
use App\Models\PrivateUserUnlockBalance;
use App\Services\SaleInfoService;
use App\Services\UserBalanceService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;

class UnlockPrivateUserTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:private-user-balance';

    protected $privateUserUnlockBalance;

    protected $userBalanceService;

    protected $saleInfoService;

    protected $userService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock private user balance command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->privateUserUnlockBalance = new PrivateUserUnlockBalance();
        $this->userBalanceService = new UserBalanceService();
        $this->saleInfoService = new SaleInfoService();
        $this->userService = new UserService();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->tokenUnlockExecution();
    }

    /**
     * Validate Metamask Transaction
     *
     */
    public function tokenUnlockExecution()
    {
        $privateUserUnlockBalanceList = $this->privateUserUnlockBalance->getPrivateUserUnlockBalances();
        $privateUserUnlockBalanceList->chunkById(100, function ($privateUserUnlockBalances) {
            foreach ($privateUserUnlockBalances as $key => $privateUserUnlockBalance) {
                $user = $this->userService->getUserByWalletAddress($privateUserUnlockBalance->wallet_address);

                $userBalance = $this->userBalanceService->getUserBalanceByTokenId(
                    $user->id,
                    $privateUserUnlockBalance->token_id
                );

                $checkDate = $this->checkReleaseDate(
                    $privateUserUnlockBalance->next_run_date,
                    $privateUserUnlockBalance->updated_at
                );

                // update status = 0 with case amount_lock_remain < unlockAmount or amount_lock_remain = 0
                // this will update after a day the last run
                if ($privateUserUnlockBalance->amount_lock_remain == 0) {
                    $privateUserUnlockBalance->status = 0;
                    $privateUserUnlockBalance->next_run_date = null;
                    $privateUserUnlockBalance->update();
                }

                if ($checkDate) {
                    //get info of token sale
                    $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule(
                        $privateUserUnlockBalance->token_sale_id
                    );
                    //the order of unlock rule
                    $orderRun = $privateUserUnlockBalance->current_order_unlock;
                    //calculate the amount to unlock
                    if ($orderRun < $tokenSaleInfo->token_unlock_rules->count()) {
                        $unlockAmount =
                        $privateUserUnlockBalance->amount_lock *
                        $tokenSaleInfo->token_unlock_rules[$orderRun]->unlock_percentages
                        / 100;
                    }
                    //unlock when status is not 0
                    if ($privateUserUnlockBalance->status != 0) {
                        UpdatePrivateUnlockBalanceJob::dispatch(
                            $privateUserUnlockBalance,
                            $userBalance,
                            $unlockAmount ? $unlockAmount : 0
                        )
                            ->onQueue(config('defines.queue.general'))
                            ->delay(now()->addSeconds(($key + 1) * 3));

                        Log::info(
                            '[SUCCESS] Unlock token for user ID: '
                            . $user->id
                            . ' - sale token ID: '
                            . $privateUserUnlockBalance->token_sale_id
                        );
                        $this->info(
                            '[SUCCESS] Unlock token for user ID: '
                            . $user->id
                            . ' - sale token ID: '
                            . $privateUserUnlockBalance->token_sale_id
                        );
                    }
                }
            }
        }, 'id');
    }

    /**
     * Check token release date
     *
     * @param  mixed  $nextRunDate, $updatedAt
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
