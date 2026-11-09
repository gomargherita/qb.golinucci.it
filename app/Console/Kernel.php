<?php

namespace App\Console;

use App\Jobs\ReconcileDoprintStuckJob;
use App\Jobs\ReconcileYousignStuckJob;
use App\Jobs\SendRenewalsJob;
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
        /*$schedule->command('mail:first_send')
            ->dailyAt('08:00') // scatta alle 8 del mattino
            //->everyTenMinutes() // utile per test
            ->appendOutputTo('send_first_email.log')
            ->emailOutputOnFailure(config('mail-extra.mail_on_failure'));*/

        // Tick only: work runs on the queue (Redis on staging, database on prod until KAN-10).
        $schedule->job(new SendRenewalsJob)
            ->dailyAt('08:00')
            ->name('mail-renew')
            ->withoutOverlapping();

        $schedule->job(new ReconcileDoprintStuckJob)
            ->hourly()
            ->name('doprint-reconcile')
            ->withoutOverlapping(55);

        $schedule->job(new ReconcileYousignStuckJob)
            ->hourly()
            ->name('yousign-reconcile')
            ->withoutOverlapping(55);
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
