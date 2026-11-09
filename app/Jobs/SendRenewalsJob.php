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
 * Daily renewal/reminder mail on the queue (KAN-10 / KAN-12).
 * Scheduler only dispatches; sending runs in a Redis worker.
 */
class SendRenewalsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 600;

    public $uniqueFor = 82800;

    public function uniqueId()
    {
        return 'mail-renew';
    }

    public function handle()
    {
        $exit = Artisan::call('mail:renew');

        Log::info('renewals.send_job', [
            'exit' => $exit,
            'output' => Artisan::output(),
        ]);
    }
}
