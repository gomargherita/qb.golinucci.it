<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Hourly DoPrint retry on the queue (KAN-10 / KAN-38).
 * Scheduler only dispatches; SOAP runs in a Redis worker.
 */
class ReconcileDoprintStuckJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 240;

    public $uniqueFor = 3300;

    public function uniqueId()
    {
        return 'doprint-reconcile-stuck';
    }

    public function handle()
    {
        $exit = Artisan::call('doprint:reconcile-stuck', [
            '--apply' => true,
            '--limit' => 20,
        ]);

        Log::info('doprint.reconcile_job', [
            'exit' => $exit,
            'output' => Artisan::output(),
        ]);
    }
}
