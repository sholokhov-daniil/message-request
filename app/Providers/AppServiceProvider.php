<?php

namespace App\Providers;

use App\Components\ComponentManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            'MessengerComponent',
            fn() => new ComponentManager
        );
    }
}
