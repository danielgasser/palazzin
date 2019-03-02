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
//Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', 'HomeController@getHome')->name('home');

    //News
    Route::get('news_reloaded', 'PostController@reloadPost');
    Route::post('news', 'PostController@savePost');
    // Reservations
    Route::get('{any}reservation/get-per-period', 'NewReservationController@getAllReservationInPeriod')->where('any', '.*');
    Route::get('new_reservation', 'NewReservationController@newReservation')->name('new_reservation');
    Route::get('all_reservations', 'NewReservationController@getUserReservations')->name('all_reservations');
    Route::post('save_reservation/{res_id?}', 'NewReservationController@saveReservation')->name('save_reservation');
    Route::post('delete_reservation', 'NewReservationController@deleteReservation')->name('delete_reservation');
    Route::any('edit_reservation/{res_id}', 'NewReservationController@editReservation')->name('edit_reservation');
    Route::get('reservation/month/v3', 'NewReservationController@getReservationsPerDateV3');
    Route::get('calendar', 'ReservationController@getReservations');

    Route::group(['middleware' => 'clerk'], function () {
        Route::post('userlist_search', 'UserController@searchUsers');
        Route::get('userlist', 'UserController@showUsers');
        Route::get('pricelist', 'RoleController@getPriceList');
        Route::post('userlist/print', 'UserController@userListPrint')->name('userlist_print');
        // v3

        // user
        Route::get('user/profile/{id?}', 'UserController@showProfile');
    });
    Route::post('user/profile', 'UserController@saveProfile');
    Route::post('user/profile/changepass', 'UserController@changePassword');
    Route::get('user/bills', 'BillController@showBills');

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

    // Keeper
    Route::group(['middleware' => 'keeper'], function () {
        Route::get('keeper/reservations', ['uses' => 'NewReservationController@AdminSearchAllReservations']);
    });

    // Admin
    Route::group(['middleware' => 'admin'], function () {
        Route::get('password/new/{pass}', 'RemindersController@manualPass');
        Route::get('admin/settings', 'SettingController@showSettings');
        Route::get('admin/calc', 'PeriodController@calculatePeriods')->name('calc-periods');
        Route::post('admin/settings', 'SettingController@setSettings')->name('save-settings');
        Route::get('admin/users/delete/{id}', 'AdminController@deleteUser');
        Route::get('admin/reservations/search', 'NewReservationController@AdminSearchAllReservations');
        Route::get('admin/reservations', 'NewReservationController@AdminGetAllReservations');

        // bills
        Route::group(['middleware' => 'clerk'], function () {
            Route::get('admin/bills', 'BillController@showBills');
            Route::get('admin/bill_list_print', 'BillController@showBillsPrint');
            Route::get('admin/bills/paid', 'BillController@payBill');
            Route::get('admin/bills/unpaid', 'BillController@unPayBill');
            Route::get('admin/bills/generate', 'BillController@generateBills');
            Route::get('admin/bills/filelist', 'BillController@getBillFilesList');
        });


        // users
        Route::get('admin/users', 'UserController@showUsers');
        Route::get('admin/users/add', 'AdminController@showAddUser');
        Route::post('admin/users/add', 'AdminController@addUser');
        Route::post('admin/users/addrole', 'RoleController@getRolesAjax');
        Route::post('admin/users/edit/delete', 'UserController@deleteRoleUser');
        Route::post('admin/users/add/sendnew', 'RemindersController@postRemindNewUser');
        Route::get('admin/users/edit/{id}', 'UserController@showEditUser');

        // Roles
        Route::get('admin/roles', 'RoleController@showRoles');
        Route::post('admin/roles', 'RoleController@searchRoles');
        Route::get('admin/roles/edit/{id}', 'RoleController@showEditRole');
        Route::post('admin/roles/edit/{id}', 'RoleController@saveRole');
        Route::post('admin/roles/rights', 'RoleController@addRightRole');
        Route::post('admin/roles/rights/delete', 'RoleController@deleteRightRole');

        // Rights
        Route::get('admin/rights', 'RightController@showRights');
        Route::post('admin/rights', 'RightController@searchRights');
        Route::get('admin/rights/edit/{id}', 'RightController@showEditRight');
        Route::post('admin/rights/edit/{id}', 'RightController@saveRight');

    });

});
