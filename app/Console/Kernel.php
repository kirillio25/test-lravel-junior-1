<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\SyncItemsToGoogleSheet::class,
        \App\Console\Commands\TestCommand::class,
        \App\Console\Commands\FetchItemsFromSheet::class,
        \App\Console\Commands\UpdateGoogleSheet::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('sync:items')->everyMinute();
    }
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
