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
Route::get('check-session', 'CronController@getSession');

//cronjobs
Route::group(['middleware' => 'guest'], function () {
    Route::get('cronjobs/bills', 'CronController@sendBill'); // after midnight
    Route::get('cronjobs/birthdays', 'CronController@sendBirthdayNotification'); // after midnight
    Route::get('cronjobs/moving', 'CronController@sendMovingNotification'); // after midnight
    Route::get('login_user/{id}', 'CronController@loginUser'); // after midnight
    Route::get('cronjobs/reservation/reminder/{sendToHousekeeper?}', 'CronController@getFutureReservations'); // after midnight
});

// test

Route::get('test', function () {
    return view('test.test');
});

Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
});
Auth::routes();

Route::group(['middleware' => ['auth', 'revalidate']], function () {

    // Clerk
    Route::group(['middleware' => ['admin', 'clerk']], function () {
        Route::get('admin/bills/filelist', 'BillController@getBillFilesList');
        Route::get('admin/bills', 'BillController@showBills');
        Route::get('admin/bills/totals', 'BillController@getAllTotals');
        Route::get('admin/bills/paid', 'BillController@payBill');
        Route::get('admin/bills/unpaid', 'BillController@unPayBill');
        Route::post('admin/send_bill', 'BillController@reSendBill');
    });

    // Users, Cöerk
    Route::group(['middleware' => ['clerk-reservator', 'revalidate']], function () {
        Route::get('/', 'HomeController@getHome')->name('home');

        Route::get('bills/totals', 'BillController@getAllTotals');
        Route::post('userlist_search', 'UserController@searchUsers');
        Route::get('userlist', 'UserController@showUsers');
        Route::get('pricelist', 'RoleController@getPriceList');
        Route::post('userlist/print', 'UserController@userListPrint')->name('userlist_print');
        Route::get('user/profile/{id}', 'UserController@showProfile');

    });

        // Users
    Route::group(['middleware' => 'reservator'], function () {
        Route::get('news', 'NewsController@getNews')->name('news');
        Route::post('notify_new_post', 'PostController@notifyNewPost');

        //News
        Route::get('news_reloaded', 'PostController@reloadPost');
        Route::post('news', 'PostController@savePost');
        Route::get('news/getone', ['uses' => 'PostController@getPostById']);
        Route::post('news/delete', ['uses' => 'PostController@deletePost']);

        // Reservations
        Route::get('{any}reservation/get-per-period', 'ReservationController@getAllReservationInPeriod')->where('any', '.*');
        Route::get('new_reservation', 'ReservationController@newReservation')->name('new_reservation');
        Route::get('all_reservations', 'ReservationController@getUserReservations')->name('all_reservations');
        Route::post('save_reservation/{res_id?}', 'ReservationController@saveReservation')->name('save_reservation');
        Route::post('delete_reservation', 'ReservationController@deleteReservation')->name('delete_reservation');
        Route::post('delete_guest', 'ReservationController@deleteGuest')->name('delete_guest');
        Route::post('check_existent', 'ReservationController@checkExistentReservation')->name('check_existent');
        Route::any('edit_reservation/{res_id}', 'ReservationController@editReservation')->name('edit_reservation');
        Route::get('reservation/month/v3', 'ReservationController@getReservationsPerDateV3');


        Route::post('user/profile/{id}', 'UserController@saveProfile')->name('save_user');
        Route::get('user/bills', 'BillController@showBills');

        // stats
        Route::get('stats_calendar', 'StatsController@showStatsReservationsCalendar');
        Route::get('stats', 'StatsController@showStatsMenu');
        Route::get('stats_bill', 'StatsController@showStatsBills');
        Route::get('stats_login', 'StatsController@showStatsLogin');
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
        Route::get('reservations', 'ReservationController@AdminGetAllReservations');

        // users
        Route::get('users', 'UserController@showUsers');
        Route::get('users/add', 'AdminController@showAddUser');
        Route::post('users/add', 'AdminController@addUser');
        Route::get('users/add/sendnew/{email?}', 'AdminController@sendPasswordMail');
        Route::post('users/activate', 'UserController@activateUser');
        Route::post('users/addrole', 'RoleController@getRolesAjax');
        Route::post('users/edit/delete', 'UserController@deleteRoleUser');
        Route::get('users/edit/{id}', 'UserController@showEditUser');
        Route::post('users/save/{id}', 'AdminController@saveUser');
        Route::post('users/addrole', 'UserController@addRoleUser');

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
        // ToDo yet unused, maybe in the future
        Route::post('rights/edit/{id}', 'RightController@saveRight');

        // Manual Passwords
        Route::get('password/new/{pass}', 'AdminController@manualPass');
        Route::get('bills/filelist', 'BillController@getBillFilesList');
    });

});
