<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
    public function __construct($unlockUserBalance, $userBalance, $unlockAmount)
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
                $this->unlockUserBalance->update();

                $this->userBalance->amount_lock -= $this->userBalance->amount_lock;
                $this->userBalance->update();
            } else {
                $this->unlockUserBalance->amount_lock_remain -= $this->unlockAmount;
                $this->unlockUserBalance->update();

                $this->userBalance->amount_lock -= $this->unlockAmount;
                $this->userBalance->update();
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
