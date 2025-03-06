<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class KaikiDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'kaiki_data',
            'App\Services\KaikiData'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
