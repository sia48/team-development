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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/test/{day}/{group_id}/{user_id}','App\Http\Controllers\ShowController@test');
Route::post('/search_user/{user_name}','App\Http\Controllers\GroupController@search');
Route::post('/register/{user_id}','App\Http\Controllers\GroupController@register');
Route::post('/inv_user/{id}/{group_id}','App\Http\Controllers\GroupController@invUser');

Route::any('/test/{day}','App\Http\Controllers\ShowController@test');
Route::any('/suito/test/{day}','App\Http\Controllers\SuitoController@test');
