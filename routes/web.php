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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('spotify')->group(function () {
    Route::get('/token', 'App\Http\Controllers\spotifyController@getApiToken');
    Route::get('/token2', 'App\Http\Controllers\spotifyController@getApiToken');
    Route::post('/search', 'App\Http\Controllers\spotifyController@search');
    Route::post('/playlists', 'App\Http\Controllers\spotifyController@fetchPlaylist');
});