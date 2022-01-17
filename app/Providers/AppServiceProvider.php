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
        Response::macro('error', function ($message, $responseCode) {
            return Response::json([
                'message' => $message,
                'status' => 'error'
            ],$responseCode);
        });

        Response::macro('success', function ($message, $responseCode) {
            return Response::json([
                'message' => $message,
                'status' => 'success'
            ],$responseCode);
        });
    }
}
