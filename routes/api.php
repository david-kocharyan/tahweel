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

    Route::prefix('user')->group(function ()
    {
        Route::post('login', 'Api\AuthController@login');
        Route::post('register', 'Api\AuthController@register');
        Route::post('refresh-token', 'Api\AuthController@refreshToken');
        Route::post("reset-password", "Api\AuthController@recoverPassword");

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('logout','Api\AuthController@logout'); // Check, if this works for ended token
            Route::get('get-user', 'Api\AuthController@getUser');
            Route::post("fcm-token", "Api\AuthController@fcmToken");
            Route::get('get-user', 'Api\AuthController@getUser');
            Route::put('edit-user', 'Api\AuthController@edit');
            Route::put('change-password', 'Api\AuthController@changePassword');
            Route::get('get-points', 'Api\AuthController@getPoints');
        });
    });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::prefix('issues')->group(function () {
            Route::get('get-issues', 'Api\IssueController@index');
        });

        Route::prefix('inspections')->group(function () {
            Route::post('request-inspection', 'Api\InspectionController@request');
            Route::get('get-inspection', 'Api\InspectionController@getInspections');
            Route::get('get-phases-totals', 'Api\InspectionController@getTotals');
            Route::get('get-inspection/{inspection_id}', 'Api\InspectionController@getInspectionDetails');
            Route::post('customer', 'Api\CustomerController@index');
            Route::get('customer', 'Api\CustomerController@getCustomer');
            Route::post('form', 'Api\InspectionFormController@index');
            Route::get('form', 'Api\InspectionFormController@getForm');
            Route::post('ask-for-inspection', 'Api\InspectionController@plumberInspectionRequest');
        });
    });


});


