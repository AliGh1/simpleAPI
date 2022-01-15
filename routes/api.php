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

Route::prefix('v1')->namespace('App\Http\Controllers\Api\v1')->group(function (){
    Route::post('/login', 'UserController@login');
    Route::post('/register', 'UserController@register');

    Route::middleware('auth:api')->group(function (){
        Route::patch('/change-password', 'UserController@changePassword');

        Route::prefix('admin')->namespace('admin')->group(function (){
            Route::resource('categories', 'CategoryController')
                ->except(['create', 'edit']);

            Route::resource('posts', 'PostController')
                ->except('create', 'edit');
        });
    });

});




