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
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::get('/fc_tokyo', 'Admin_FC_TOKYO_Controller@index')->name('admin.fc_tokyo');
    Route::get('/why_you_run', 'AdminWhyYouRunController@index')->name('admin.why_you_run');
    Route::get('/why_you_run_quality', 'AdminWhyYouRunController@index_quality')->name('admin.why_you_run_quality');
    Route::get('/golf', 'AdminGolfController@index')->name('admin.golf');
});

// fc_tokyo
Route::get('/fc_tokyo', 'FcTokyoApplicationController@index');
Route::post('fc_tokyo/aplication', 'FcTokyoApplicationController@aplication')->name('fcTokyo.aplication');
Route::get('fc_tokyo/complete', 'FcTokyoApplicationController@complete')->name('fcTokyo.complete');

// why_you_run
Route::group(['prefix'=>'why_you_run'], function(){
    Route::get('/10k_charge', 'WhyYouRunController@index')->name('why_you_run.index');
    Route::post('/10k_charge_register', 'WhyYouRunController@register')->name('why_you_run.register');
    Route::get('/10k_charge_complete', 'WhyYouRunController@complete')->name('why_you_run.complete');

    Route::get('/quality', 'WhyYouRunQualityController@index')->name('why_you_run_quality.index');
    Route::post('/quality_register', 'WhyYouRunQualityController@register')->name('why_you_run_quality.register');
    Route::get('/quality_complete', 'WhyYouRunQualityController@complete')->name('why_you_run_quality.complete');
});

// Golf  GolfHolidayCampaignController
Route::get('/golf', 'GolfHolidayCampaignController@index')->name('golf.index');
Route::post('golf/aplication', 'GolfHolidayCampaignController@register')->name('golf.aplication');
Route::get('golf/complete', 'GolfHolidayCampaignController@complete')->name('golf.complete');

// php artisan make:model Models/GolfHolidayCampaign -mc
// php artisan make:controller AdminGolfController
