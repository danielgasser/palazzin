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
    Route::any('edit_reservation/{res_id}', 'NewReservationController@editReservation')->name('edit_reservation');
    //Route::post('check_existent', 'NewReservationController@checkExistentReservation')->name('check_existent');
    Route::get('reservation/month/v3', ['uses' => 'NewReservationController@getReservationsPerDateV3']);
    Route::get('calendar', 'ReservationController@getReservations');

    Route::post('userlist_search', 'UserController@searchUsers');
    Route::get('userlist', 'UserController@showUsers');
    Route::get('pricelist', 'RoleController@getPriceList');
    // v3

    // stats
    Route::get('stats_calendar', 'StatsController@showStatsReservationsCalendar');
    Route::get('stats', 'StatsController@showStatsMenu');
    Route::get('stats_bill', 'StatsController@showStatsBills');
    Route::get('stats_login', 'StatsController@showStatsLogin');
    Route::post('stats_print', 'StatsController@printStats');
    Route::get('stats_bill_total', 'StatsController@showStatsBillsTotal');
    Route::get('stats_bill_total_year', 'StatsController@showStatsBillsTotalPerYear');
    Route::get('stats_chron', 'StatsController@showStatsReservations');
    Route::get('stats_chron_family_night_total', 'StatsController@showStatsNightsTotal');
    Route::get('stats_chron_guest_night_total', 'StatsController@showStatsNightsTotalGuests');
    Route::get('stats_calendar_month', 'StatsController@showStatsReservationsCalendarPerMonth');
    Route::get('stats_calendar_total_day', 'StatsController@showStatsReservationsCalendarTotalPerDay');
    Route::get('stats_list', 'StatsController@showStatsPrint');
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

        //bills
        Route::get('admin/bills', 'BillController@showBills');
        Route::get('admin/bill_list_print', 'BillController@showBillsPrint');
        Route::get('admin/bills/paid', 'BillController@payBill');
        Route::get('admin/bills/unpaid', 'BillController@unPayBill');
        Route::get('admin/bills/generate', 'BillController@generateBills');
    });

});
