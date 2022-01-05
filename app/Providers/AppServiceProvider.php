<?php

namespace App\Providers;

use Google\Client;
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
        $this->app->singleton(Client::class, function () {
            // For Feature Test
            // $client = app(Client::class);
            // For real controller
            $client = new Client();
            $config = config('services.google-drive');
            $client->setClientId($config['id']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect_uri']);
            return $client;
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
