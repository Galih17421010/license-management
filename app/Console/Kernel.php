<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule):void
    {
        $schedule->command('licenses:check-expiry')
                ->dailyAt('08:00')
                ->timezone('Asia/Jakarta')
                ->withoutOverlapping()
                ->runInBackground()
                ->appendOutputTo(storage_path('logs/license-check.log'));

        $schedule->command('backup:run')
                ->weekly()
                ->sundays()
                ->at('02:00')
                ->timezone('Asia/Jakarta');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}