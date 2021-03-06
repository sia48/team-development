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

Route::get('/{year}/{month}','App\Http\Controllers\ShowController@showCalendar')->name('calendar');
Route::post('/request', 'App\Http\Controllers\ShowController@requestCalendar')->name('request');

// プロフィール画面
Route::post('/profile/{id}','App\Http\Controllers\ShowController@profile')->name('profile');

// スケジュール画面
Route::post('/store/{year}/{month}','App\Http\Controllers\ShowController@store')->name('store');
Route::post('/edit/{year}/{month}/{id}', 'App\Http\Controllers\ShowController@edit')->name('edit'); 
Route::post('/delete/{year}/{month}/{id}', 'App\Http\Controllers\ShowController@destroy')->name('delete');

// グループ画面に遷移する
Route::get('/group','App\Http\Controllers\GroupController@group')->name('group');
Route::post('/group_store/{num}','App\Http\Controllers\GroupController@store')->name('group_store');
Route::get('/group_show','App\Http\Controllers\GroupController@show')->name('group_show');
Route::get('/group/detail/{id}','App\Http\Controllers\GroupController@detail')->name('group_detail');
Route::post('/group/update/{id}','App\Http\Controllers\GroupController@update')->name('group_update');
Route::post('/group/delete/{id}','App\Http\Controllers\GroupController@destroy')->name('group_delete');
Route::post('/group/exit/{id}','App\Http\Controllers\GroupController@exit');
Route::post('/group/delete_member/{user_id}/{id}','App\Http\Controllers\GroupController@destroyMember')->name('member_delete');
Route::post('/group_invitation/{num}','App\Http\Controllers\GroupController@invitation');
Route::post('/group_select/{id}','App\Http\Controllers\GroupController@select');

// ホーム画面（家計簿）
Route::get('/', function () {
    return redirect()->route('suito', ['year' => date('Y'), 'month' => date('n')]);
});
Route::get('/suito/{year}/{month}','App\Http\Controllers\SuitoController@showSuito')->name('suito');

// MF登録画面

Route::post('/suito_store/{year}/{month}','App\Http\Controllers\SuitoController@suitoStore')->name('suito_store');
Route::post('/suito_edit/{year}/{month}/{id}', 'App\Http\Controllers\SuitoController@suitoedit')->name('suito_edit'); 
Route::post('/suito_delete/{year}/{month}/{id}', 'App\Http\Controllers\SuitoController@suitodestroy')->name('suito_delete');

// MF登録合計表示
Route::get('/suitos/{year}/{month}','App\Http\Controllers\SuitoController@suitoIncome');

Route::get('/money','App\Http\Controllers\MoneyController@money')->name('money');

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return redirect()->route('calendar', ['year' => date('Y'), 'month' => date('n')]);
})->name('dashboard');