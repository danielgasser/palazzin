<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->getSharedData();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function getSharedData()
    {
        view()->composer('*', 'App\Http\ViewComposers\ViewSessionComposer');
        view()->composer('*', 'App\Http\ViewComposers\ViewDataComposer');
        view()->composer('*', 'App\Http\ViewComposers\ViewSettingsComposer');
        view()->composer(['user.login', 'layout.footer'], 'App\Http\ViewComposers\ViewLoginComposer');
    }
}
