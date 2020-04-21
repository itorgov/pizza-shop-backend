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

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', 'RegisterController@register');
        Route::post('login', 'LoginController@login');
    });

    Route::post('logout', 'LogoutController@logout')->middleware('auth');
});
