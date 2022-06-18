<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\EndSessions',
        'App\Console\Commands\BackupDataBaseDaily',
        'App\Console\Commands\DeleteHolidaysTimedOut',
        'App\Console\Commands\Attendances',
        'App\Console\Commands\EndSubscriptions'


    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('end:sessions')->everyMinute();
        $schedule->command('store:backup_daily')->daily();
        $schedule->command('delete:holidays')->daily();
        $schedule->command('check:attendances')->everyMinute();
        $schedule->command('end:subscriptions')->monthly();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
