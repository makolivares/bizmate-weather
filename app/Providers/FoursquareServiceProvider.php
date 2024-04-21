<?php

namespace App\Providers;

use App\APIServices\FoursquareService;
use Illuminate\Support\ServiceProvider;

class FoursquareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FoursquareService::class, function ($app) {
            return new FoursquareService();
        });
    }

}
