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

Route::group(["prefix" => "v1"], function(){

    Route::prefix('user')->group(function () {
        Route::post('login', 'Api\AuthController@login');
        Route::post('register', 'Api\AuthController@register');
        Route::post('refresh-token', 'Api\AuthController@refreshToken');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('logout','Api\AuthController@logout'); // Check, if this works for ended token
            Route::get('get-user', 'Api\AuthController@getUser');
        });
    });

    Route::post("send-email", "Api\AuthController@recoverPassword");

});


