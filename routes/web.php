<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (){
    return redirect('/admin');
})->middleware("auth:web");

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth:web'], function () {
    Route::get('/', 'AdminController@index');
    Route::resource('plumbers', 'PlumberController');
    Route::resource('inspectors', 'InspectorController');
    Route::resource('inspections', 'InspectionController');
    Route::resource('products', 'ProductController');
    Route::resource('certificates', 'CertificateController');
    Route::resource('notifications', 'NotificationController');
    Route::resource('redeems', 'InspectorController');
});
