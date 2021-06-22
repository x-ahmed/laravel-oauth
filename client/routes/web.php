<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OauthController;

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

Route::group(['as' => 'oauth.', 'prefix' => 'oauth'], function () {
    Route::get('refresh', [OauthController::class, 'refresh'])->name('refresh');
});

// Redirect from this client to the oauth server requesting an authorization token
Route::group(['as' => 'oauth.', 'prefix' => 'oauth'], function () {
    Route::get('callback', [OauthController::class, 'callback'])->name('callback');
    Route::get('redirect', [OauthController::class, 'redirect'])->name('redirect');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')
    ->name('home')
    ->middleware('auth');
