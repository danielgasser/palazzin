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
    if (Auth::check()) {
        return view('welcome');
    }
    return view('auth.login');
});
Route::get('check-session', 'HomeController@getHome');

Auth::routes();
//Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );

Route::group(['middleware' => 'auth'], function () {

    // Clerk
    Route::group(['middleware' => ['admin', 'clerk']], function () {
        Route::get('admin/bills/filelist', 'BillController@getBillFilesList');
        Route::get('admin/bills', 'BillController@showBills');
        Route::get('admin/bills/paid', 'BillController@payBill');
        Route::get('admin/bills/unpaid', 'BillController@unPayBill');
    });

    // Users, Cöerk
    Route::group(['middleware' => 'clerk-reservator'], function () {

        Route::post('userlist_search', 'UserController@searchUsers');
        Route::get('userlist', 'UserController@showUsers');
        Route::get('pricelist', 'RoleController@getPriceList');
        Route::post('userlist/print', 'UserController@userListPrint')->name('userlist_print');

    });
    // Users
    Route::group(['middleware' => 'reservator'], function () {
        Route::get('home', 'HomeController@getHome')->name('home');

        //News
        Route::get('news_reloaded', 'PostController@reloadPost');
        Route::post('news', 'PostController@savePost');
        Route::get('news/getone', ['uses' => 'PostController@getPostById']);
        Route::post('news/delete', ['uses' => 'PostController@deletePost']);

        // Reservations
        Route::get('{any}reservation/get-per-period', 'NewReservationController@getAllReservationInPeriod')->where('any', '.*');
        Route::get('new_reservation', 'NewReservationController@newReservation')->name('new_reservation');
        Route::get('all_reservations', 'NewReservationController@getUserReservations')->name('all_reservations');
        Route::post('save_reservation/{res_id?}', 'NewReservationController@saveReservation')->name('save_reservation');
        Route::post('delete_reservation', 'NewReservationController@deleteReservation')->name('delete_reservation');
        Route::any('edit_reservation/{res_id}', 'NewReservationController@editReservation')->name('edit_reservation');
        Route::get('reservation/month/v3', 'NewReservationController@getReservationsPerDateV3');

        Route::get('user/profile/{id?}', 'UserController@showProfile');
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
    });

    // Admin
    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('settings', 'SettingController@showSettings');
        Route::get('calc', 'PeriodController@calculatePeriods')->name('calc-periods');
        Route::post('settings', 'SettingController@setSettings')->name('save-settings');
        Route::get('users/delete/{id}', 'AdminController@deleteUser');
        Route::get('reservations/search', 'NewReservationController@AdminSearchAllReservations');
        Route::get('reservations', 'NewReservationController@AdminGetAllReservations');

        // users
        Route::get('users', 'UserController@showUsers');
        Route::get('users/add', 'AdminController@showAddUser');
        Route::post('users/add', 'AdminController@addUser');
        Route::post('users/activate', 'UserController@activateUser');
        Route::post('users/addrole', 'RoleController@getRolesAjax');
        Route::post('users/edit/delete', 'UserController@deleteRoleUser');
        Route::post('users/add/sendnew', 'AdminController@postRemindNewUser');
        Route::get('users/edit/{id}', 'UserController@showEditUser');
        Route::post('users/edit/{id}', 'UserController@addRoleUser');

        // Roles
        Route::get('roles', 'RoleController@showRoles');
        Route::post('roles', 'RoleController@searchRoles');
        Route::get('roles/edit/{id}', 'RoleController@showEditRole');
        Route::post('roles/edit/{id}', 'RoleController@saveRole');
        Route::post('roles/rights', 'RoleController@addRightRole');
        Route::post('roles/rights/delete', 'RoleController@deleteRightRole');

        // Rights
        Route::get('rights', 'RightController@showRights');
        Route::post('rights', 'RightController@searchRights');
        Route::get('rights/edit/{id}', 'RightController@showEditRight');
        Route::post('rights/edit/{id}', 'RightController@saveRight');

        // Manual Passwords
        Route::get('password/new/{pass}', 'AdminController@manualPass');
        Route::get('bills/filelist', 'BillController@getBillFilesList');

        Route::get('cronjobs/bills', 'BillController@cronBills');

    });

});
