<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckStatusNftAuctionCommand::class,
        Commands\UpdateExchangeRateCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:nft-auction-history')->everyThreeMinutes();
        $schedule->command('update:ticket')->cron(config('defines.ticket.update_ticket_cron_time'));
        $schedule->command('refund:coupon')->everyFiveMinutes();
        $schedule->command('telescope:prune')->daily();
        $schedule->command('sanctum:prune-expired --hours=24')->daily();
        $schedule->command('update:exchange-rate')->cron(config('defines.exchange_rate.cron'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
