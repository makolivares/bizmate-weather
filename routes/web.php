<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-weather', 'App\Http\Controllers\WeatherController@getWeather')->middleware('check.api.request');
Route::get('/get-places', 'App\Http\Controllers\WeatherController@getPlaces')->middleware('check.api.request');
