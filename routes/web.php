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
Route::get('/', fn() => 'Yoki-api - v1');
Route::get('/privacy-policy', fn() => 'Privacy policy');
Route::test('/test', function () {
    dd(\Stevebauman\Location\Facades\Location::get());
});

Route::get('/test2', function () {
    dd(request());
});

Route::get('/test3', function () {
    dd(request()->ip());
});


