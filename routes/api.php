<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API version 1
Route::prefix('v1')->namespace('App\Http\Controllers\Api\v1')->group(function (){
    // Users Route
    Route::namespace('Users')->name('v1.')->group(function (){
        // Authentication Routes
        Route::post('/login', 'UserController@login')->name('login');
        Route::post('/register', 'UserController@register')->name('register');
        Route::patch('/change-password', 'UserController@changePassword')
            ->middleware('auth:api')->name('change-password');

        Route::resource('posts', 'PostController')
            ->only('index', 'show');

        Route::resource('categories', 'CategoryController')
            ->only(['index', 'show']);

        Route::middleware('auth:api')->group(function (){
            Route::post('/like', 'LikeController@like')->name('like');

            Route::resource('comments', 'CommentController')
                ->only(['store', 'update', 'destroy']);
        });
    });

    // Admins Route
    Route::middleware('auth:api')->namespace('admins')->prefix('admins')
        ->name('v1.admins.')->group(function (){
            Route::resource('posts', 'PostController')
                ->except('create', 'edit', 'show');

            Route::resource('categories', 'CategoryController')
                ->only(['store', 'update', 'destroy']);
        });
});
