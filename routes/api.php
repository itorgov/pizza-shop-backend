<?php

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

Route::get('delivery-fees', 'DeliveryFeesController@index');
Route::get('pizzas', 'PizzasController@index');
Route::get('orders', 'OrdersController@index')->middleware('auth');
Route::post('orders', 'OrdersController@store');
