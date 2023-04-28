<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('mail:first_send')
            ->dailyAt('08:00') // scatta alle 8 del mattino
            //->everyTenMinutes() // utile per test
            ->appendOutputTo('send_first_email.log')
            ->emailOutputOnFailure(config('mail-extra.mail_on_failure'));

            $schedule->command('mail:renew')
            ->dailyAt('08:00') // scatta alle 8 del mattino
            //->everyTenMinutes() // utile per test
            ->appendOutputTo('renew_email.log')
            ->emailOutputOnFailure(config('renew_email.mail_on_failure'));
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
