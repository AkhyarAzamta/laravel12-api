<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PokeApiService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PokeApiService::class, function ($app) {
            return new PokeApiService();
        });
    }

    public function boot()
    {
        //
    }
}