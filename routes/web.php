<?php

use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;

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
Route::get('/', fn() => 'Yoki-api - v1');
Route::get('/privacy-policy', fn() => 'Privacy policy');

Route::get('/test', function () {

    $position = Location::get(request()->server('HTTP_X_FORWARDED_FOR'));

    dd($position);
});
