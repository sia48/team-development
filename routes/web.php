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

// 会員登録画面
Route::get('/user','App\Http\Controllers\UserController@user')->name('user');

// ログイン画面
Route::get('/rogin','App\Http\Controllers\UserController@rogin')->name('rogin');

// ホーム画面（スケジュール）
//Route::get('/', function () {
//    return redirect()->route('calendar', ['year' => date('Y'), 'month' => date('n')]);
//});
Route::get('/{year}/{month}','App\Http\Controllers\ShowController@showCalendar')->name('calendar');
Route::post('/request', 'App\Http\Controllers\ShowController@requestCalendar')->name('request');

// プロフィール画面
Route::get('/profile','App\Http\Controllers\UserController@profile')->name('profile');

// スケジュール画面
Route::post('/store/{year}/{month}','App\Http\Controllers\ShowController@store')->name('store');
Route::post('/edit/{year}/{month}/{id}', 'App\Http\Controllers\ShowController@edit')->name('edit'); 
Route::post('/delete/{year}/{month}/{id}', 'App\Http\Controllers\ShowController@destroy')->name('delete');

// グループ画面に遷移する
Route::get('/group','App\Http\Controllers\GroupController@group')->name('group');
Route::post('/group_store','App\Http\Controllers\GroupController@store')->name('group_store');
Route::get('/group_show','App\Http\Controllers\GroupController@show')->name('group_show');
Route::get('/group/detail/{id}','App\Http\Controllers\GroupController@detail')->name('group_detail');
Route::post('/group/update/{id}','App\Http\Controllers\GroupController@update')->name('group_update');
Route::post('/group/delete/{id}','App\Http\Controllers\GroupController@destroy')->name('group_delete');

// ホーム画面（家計簿）
Route::get('/home','App\Http\Controllers\MoneyController@home')->name('home');

// MF登録画面
Route::get('/money','App\Http\Controllers\MoneyController@money')->name('money');

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return redirect()->route('calendar', ['year' => date('Y'), 'month' => date('n')]);
})->name('dashboard');