<?php

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
    // return view('welcome');
    return redirect()->action('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/fc_tokyo', 'FcTokyoApplicationController@index');
Route::post('fc_tokyo/aplication', 'FcTokyoApplicationController@aplication')->name('fcTokyo.aplication');
Route::get('fc_tokyo/complete', 'FcTokyoApplicationController@complete')->name('fcTokyo.complete');





// admin
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::get('/', 'AdminController@index')->name('admin.index');
});

