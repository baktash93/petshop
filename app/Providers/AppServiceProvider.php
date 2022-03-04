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
        $this->app->singleton(\App\Interfaces\IAuthTokenService::class, function ($app) {
            return new \App\Services\JWTAuthTokenService();
        });
        $this->app->singleton(\App\Interfaces\ITokenStoreService::class, function ($app) {
            return new \App\Services\JwtTokenStoreProviderService();
        });
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
