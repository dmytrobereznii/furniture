<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $ideHelper = \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class;

        if ($this->app->isLocal() && class_exists($ideHelper)) {
            $this->app->register($ideHelper);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
