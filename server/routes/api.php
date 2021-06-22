<?php

use App\Post;
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

Route::group(['middleware' => 'auth:api'], function () {

    // https://laravel.com/docs/7.x/passport#checking-scopes
    Route::middleware('scope:view-posts')->get('posts', function () {
        return Post::all();
    });

    // https://laravel.com/docs/7.x/passport#checking-scopes
    Route::middleware('scope:view-user')->get('user', function (Request $request) {
        return $request->user();
    });
});
