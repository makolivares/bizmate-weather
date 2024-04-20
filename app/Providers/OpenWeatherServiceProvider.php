<?php

namespace App\Providers;

use App\APIServices\OpenWeatherAPI;
use Illuminate\Support\ServiceProvider;

class OpenWeatherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OpenWeatherAPI::class, function ($app) {
            return new OpenWeatherAPI(env('OPEN_WEATHER_API_KEY'));
        });
    }

}
