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
Route::get('help/{topic?}', 'HelpController@showHelp');
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'HomeController@getHome')->name('home');

    //News
    Route::get('news_reloaded', ['uses' => 'PostController@reloadPost']);
    Route::post('news', ['uses' => 'PostController@savePost']);
    // Reservations
    Route::get('{any}reservation/get-per-period', 'NewReservationController@getAllReservationInPeriod')->where('any', '.*');
    Route::get('new_reservation', 'NewReservationController@newReservation')->name('new_reservation');
    Route::get('all_reservations', 'NewReservationController@getUserReservations')->name('all_reservations');
    Route::post('save_reservation/{res_id?}', 'NewReservationController@saveReservation')->name('save_reservation');
    Route::post('delete_reservation', 'NewReservationController@deleteReservation')->name('delete_reservation');
    Route::post('edit_reservation/{res_id}', 'NewReservationController@editReservation')->name('edit_reservation');
    Route::post('check_existent', 'NewReservationController@checkExistentReservation')->name('check_existent');
    Route::get('reservation/month/v3', ['uses' => 'NewReservationController@getReservationsPerDateV3']);
    Route::get('calendar', 'ReservationController@getReservations');

    Route::post('userlist_search', 'UserController@searchUsers');
    Route::get('userlist', 'UserController@showUsers');
    // v3

    // stats
    // ToDo change all stats route away from admin
    Route::get('admin/stats_calendar', 'StatsController@showStatsReservationsCalendar');

    //ToDo keeper reservations
    // Admin
    Route::group(['middleware' => 'admin'], function () {
        Route::get('password/new/{pass}', 'RemindersController@manualPass');
        Route::get('admin/settings', 'SettingController@showSettings');
        Route::get('admin/calc', 'PeriodController@calculatePeriods')->name('calc-periods');
        Route::post('admin/settings', 'SettingController@setSettings')->name('save-settings');
        Route::get('admin/users/delete/{id}', 'AdminController@deleteUser');
        Route::get('admin/reservations/search', ['uses' => 'NewReservationController@AdminSearchAllReservations']);
        Route::get('admin/reservations', ['uses' => 'NewReservationController@AdminGetAllReservations']);
    });

});
