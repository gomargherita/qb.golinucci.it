<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\LloydsDoPrint;

class LloydsDoPrintProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\LloydsDoPrint', function ($app) {
            return new LloydsDoPrint();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
