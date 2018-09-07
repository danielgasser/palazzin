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
    return view('welcome');
});
Route::get('news', function () {
    return view('news');
});
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'HomeController@getHome')->name('home');
    Route::get('news_reloaded', ['uses' => 'PostController@reloadPost']);
    // v3
    Route::get('reservation/get-per-period/{pId}', ['uses' => 'NewReservationController@getAllReservationInPeriod']);
    Route::get('new_reservation', 'NewReservationController@getNewReservations')->name('new_reservation');
    Route::post('new_reservation', 'NewReservationController@saveReservations')->name('save_reservation');
    Route::get('reservation/month/v3', ['uses' => 'NewReservationController@getReservationsPerDateV3']);
    // v3

    // stats
    // ToDo change all stats route away from admin
    Route::get('admin/stats_calendar', 'StatsController@showStatsReservationsCalendar');

    // Admin
    Route::group(['middleware' => 'admin'], function () {
        Route::get('password/new/{pass}', 'RemindersController@manualPass');
    });

});
