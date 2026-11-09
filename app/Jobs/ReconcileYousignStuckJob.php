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
 * Hourly YouSign recovery on Redis (KAN-5 / KAN-10).
 */
class ReconcileYousignStuckJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 240;

    public $uniqueFor = 3300;

    public function uniqueId()
    {
        return 'yousign-reconcile-stuck';
    }

    public function handle()
    {
        $exit = Artisan::call('yousign:reconcile-stuck', [
            '--apply' => true,
            '--limit' => 20,
        ]);

        Log::info('yousign.reconcile_job', [
            'exit' => $exit,
            'output' => Artisan::output(),
        ]);
    }
}
