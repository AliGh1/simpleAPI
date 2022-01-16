<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('forbidden', function () {
            return Response::json([
                'message' => '403 Forbidden',
                'status' => 'error'
            ],\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
        });
    }
}
