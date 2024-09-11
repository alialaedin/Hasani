<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static $routesAreCached = false;
    public static $configurationIsCached = false;
    public static $runningInConsole = true;
    
    public function register(): void
    {
        static::$configurationIsCached = $this->app->configurationIsCached();
        static::$routesAreCached = $this->app->routesAreCached();
        static::$runningInConsole = $this->app->runningInConsole();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
