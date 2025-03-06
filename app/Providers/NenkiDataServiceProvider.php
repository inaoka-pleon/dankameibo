<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NenkiDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'nenki_data',
            'App\Services\NenkiData'
        );
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
