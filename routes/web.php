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

// admin
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::get('/fc_tokyo', 'Admin_FC_TOKYO_Controller@index')->name('admin.fc_tokyo');
});


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/fc_tokyo', 'FcTokyoApplicationController@index');
Route::post('fc_tokyo/aplication', 'FcTokyoApplicationController@aplication')->name('fcTokyo.aplication');
Route::get('fc_tokyo/complete', 'FcTokyoApplicationController@complete')->name('fcTokyo.complete');





Route::group(['prefix'=>'why_you_run'], function(){
    Route::get('/', 'WhyYouRunController@index')->name('why_you_run.index');
    Route::post('/register', 'WhyYouRunController@register')->name('why_you_run.register');
});
