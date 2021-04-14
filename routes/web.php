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
Route::post('a/a', 'FcTokyoApplicationController@aplication')->name('fcTokyo.aplication');





// admin
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::get('/', 'AdminController@index')->name('admin.index');
});

