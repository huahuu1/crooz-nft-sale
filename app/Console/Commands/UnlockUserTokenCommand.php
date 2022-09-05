<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUnlockBalanceJob;
use Illuminate\Console\Command;
use App\Models\UnlockUserBalance;
use App\Services\UserBalanceService;
use Carbon\Carbon;
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

                $checkDate = $this->checkReleaseDate($unlockUserBalance->token_sale->end_date, $unlockUserBalance->token_sale->lock_info->lock_day, $unlockUserBalance->updated_at);

                if ($unlockUserBalance->amount_lock_remain == 0) {
                    $unlockUserBalance->status = 0;
                    $unlockUserBalance->update();
                }

                if ($checkDate) {
                    $unlockAmount = $unlockUserBalance->amount_lock * $unlockUserBalance->token_sale->lock_info->unlock_percentages / 100;

                    if ($unlockUserBalance->status != 0) {
                        UpdateUnlockBalanceJob::dispatch($unlockUserBalance, $userBalance, $unlockAmount)->delay(now()->addSeconds(($key + 1) * 3));
                    }

                    Log::info('[SUCCESS] Unlock token for user ID: '.$unlockUserBalance->user_id . ' - sale token ID: ' . $unlockUserBalance->token_sale_id);
                    $this->info('[SUCCESS] Unlock token for user ID: '.$unlockUserBalance->user_id . ' - sale token ID: ' . $unlockUserBalance->token_sale_id);
                }
            }
        }, 'id');
    }

    /**
     * Check token release date
     *
     * @param  mixed  $endDate, $lockDay
     * @return boolean
     */
    public function checkReleaseDate($endDate, $lockDay, $updatedAt)
    {
        $today = Carbon::now();
        $endDate = new Carbon($endDate);
        $updatedAt = new Carbon($updatedAt);
        $dueDate = $endDate->addDays($lockDay);

        return ($today->eq($dueDate) || $today->gt($dueDate) && ($today->diffInDays($updatedAt) > $lockDay));
    }
}
