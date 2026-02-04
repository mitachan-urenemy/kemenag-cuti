<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Barryvdh\Snappy\ServiceProvider as SnappyServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        class_alias('Barryvdh\Snappy\Facades\SnappyPdf', 'PDF');
        $this->app->register(SnappyServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
