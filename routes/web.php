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

Route::get('lang/{lang}', function ($lang) {
    $l = Session::put('chosenlang', $lang);
    return redirect(URL::previous());
    //return Redirect::back();
});
Route::post('js-errors', ['uses' => 'HomeController@saveJSErrors']);

Route::get('help/{topic?}', ['uses' => 'HelpController@showHelp']);
Route::post('help', ['uses' => 'HelpController@getDataJson']);
Route::get('/', function ()  {
    if (Auth::check() && User::checkUsersOldWinBrowser() == 0) {
        return redirect('home');
    }

    return view('user.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('get-session', ['uses' => 'HomeController@getSession']);
    Route::get('infos', ['uses' => 'HomeController@showInfos']);
    Route::post('infos', ['uses' => 'HomeController@sendInfos']);
    Route::get('home', ['uses' => 'HomeController@getHome']);
    Route::group(['only' => ['manager', 'admin', 'reservator']], function () {
        Route::get('reservation/savecaldate/{date}/{clan?}', function ($date) {
            if ($date == null) {
                return Session::put('currentCalendarDate', time());
            }
            Session::put('currentCalendarDate', $date);
        });
    });
    Route::group(['before' => ['clerk, admin']], function () {
        Route::get('admin/bills', ['uses' => 'BillController@showBills']);
        Route::get('admin/bills/filelist', ['uses' => 'BillController@getBillFilesList']);
        Route::get('admin/bills/paid', ['uses' => 'BillController@payBill']);
        Route::post('userlist', ['uses' => 'UserController@searchUsers']);
        Route::get('userlist', ['uses' => 'UserController@showUsers']);
        Route::get('userlist/sendmail', ['uses' => 'UserController@sendMailToUsers']);
        Route::get('news_reloaded', ['uses' => 'PostController@reloadPost']);
        Route::get('news', ['uses' => 'PostController@showPost']);
        Route::post('news', ['uses' => 'PostController@savePost']);
        Route::get('bill/search', ['uses' => 'BillController@searchAllBills']);
        Route::get('bill/search_billno', ['uses' => 'BillController@searchByBillNo']);
        Route::get('bill/search/users', ['uses' => 'BillController@searchAllBillsUser']);
    });
    Route::group(['before' => ['reservator, admin']], function () {
        /**
         * v3
         */
        Route::get('reservation/get-per-period/{pId}', ['uses' => 'NewReservationController@getAllReservationInPeriod']);
        Route::get('new_reservation', ['uses' => 'NewReservationController@getNewReservations']);
        Route::get('reservation/month/v3', ['uses' => 'NewReservationController@getReservationsPerDateV3']);
        /**
         * v3
         */
        Route::get('admin/stats', ['uses' => 'StatsController@showStatsMenu']);
        Route::get('admin/stats_bill', ['uses' => 'StatsController@showStatsBills']);
        // ToDo
        Route::get('admin/stats_login', ['uses' => 'StatsController@showStatsLogin']);
        Route::post('admin/stats_print', ['uses' => 'StatsController@printStats']);
        Route::get('admin/stats_bill_total', ['uses' => 'StatsController@showStatsBillsTotal']);
        Route::get('admin/stats_bill_total_year', ['uses' => 'StatsController@showStatsBillsTotalPerYear']);
        Route::get('admin/stats_chron', ['uses' => 'StatsController@showStatsReservations']);
        Route::get('admin/stats_chron_family_night_total', ['uses' => 'StatsController@showStatsNightsTotal']);
        Route::get('admin/stats_calendar', ['uses' => 'StatsController@showStatsReservationsCalendar']);
        Route::get('admin/stats_chron_guest_night_total', ['uses' => 'StatsController@showStatsNightsTotalGuests']);
        Route::get('admin/stats_calendar_month', ['uses' => 'StatsController@showStatsReservationsCalendarPerMonth']);
        Route::get('admin/stats_calendar_total_day', ['uses' => 'StatsController@showStatsReservationsCalendarTotalPerDay']);
        Route::get('reservation', ['uses' => 'ReservationController@getReservations']);
        Route::post('reservation/savelocal', ['uses' => 'ReservationController@saveLocalStorage']);
        // Reservations
        Route::get('user/reservations', ['uses' => 'ReservationController@showAllReservations']);
        Route::get('reservation/guestlist', ['uses' => 'RoleController@getRoleForeignClan']);
        Route::get('reservation/getuser', ['uses' => 'UserController@getAuthUserId']);
        //Route::get('reservation/get/{id}', array('uses' => 'UserController@getAuthUserId'));
        Route::post('reservation/guests/save', ['uses' => 'ReservationController@saveNewGuest']);
        Route::post('reservation/guests/delete', ['uses' => 'ReservationController@deleteGuest']);
        Route::post('reservation/new', ['uses' => 'ReservationController@saveReservation']);
        Route::post('reservation/session', ['uses' => 'ReservationController@putToSession']);
        Route::get('user/reservations/search', ['uses' => 'ReservationController@searchAllReservations']);
        Route::get('admin/reservations/search', ['uses' => 'ReservationController@searchAllReservations']);
        Route::post('reservation/delete', ['uses' => 'ReservationController@deleteReservation']);
        Route::get('reservation/edit', ['uses' => 'ReservationController@editUserReservation']);
        Route::get('reservation/month', ['uses' => 'ReservationController@getReservationsPerDate']);
        Route::get('reservation/period', ['uses' => 'PeriodController@getPeriodsPerMonth']);
        Route::get('periods/all', ['uses' => 'PeriodController@getAllPeriodsTimeLine']);
        Route::get('period/current', ['uses' => 'PeriodController@getCurrentPeriod']);
        Route::get('pricelist', ['uses' => 'RoleController@getPriceList']);
        Route::post('userlist/savedata', function () {
            File::put(public_path() . '/files/temp/search.html', Input::get('html_data'));
        });
        Route::post('admin/userlist/savedata', function () {
            File::put(public_path() . '/files/temp/search.html', Input::get('html_data'));
        });
        Route::get('userlist/print', function () {
            // Tools::dd(File::get(public_path() . '/files/temp/search.html'), true);
            $mpdf = new mPDF();
            $css = File::get(public_path() . '/assets/css/print.css');
            $filename = trans('admin.user.title') . '.pdf';
            $mpdf->addPage('L', 'BLANK', 'BLANK', '1');
            $mpdf->WriteHTML($css, 1);
            $mpdf->WriteHTML(File::get(public_path() . '/files/temp/search.html'), 2);
            $mpdf->Output($filename, 'I');
            //exit;
        });
        Route::get('users/simplelist', ['uses' => 'UserController@simpleUsersList']);
        Route::get('users/notthisclanlist', ['uses' => 'UserController@counterClanUsersList']);
        Route::get('user/bills', ['uses' => 'BillController@showBills']);
        Route::get('news_reloaded', ['uses' => 'PostController@reloadPost']);
        Route::get('news', ['uses' => 'PostController@showPost']);
        Route::post('news', ['uses' => 'PostController@savePost']);
        Route::get('news/getone', ['uses' => 'PostController@getPostById']);
        Route::get('news/getcomments', ['uses' => 'CommentController@getMoreComments']);
        Route::get('news/getcomment', ['uses' => 'CommentController@getCommentById']);
        Route::post('news/addcomment', ['uses' => 'CommentController@addComment']);
        Route::post('news/delete', ['uses' => 'PostController@deletePost']);
        Route::get('news/deletecomment', ['uses' => 'CommentController@deleteComment']);
    });

    Route::group(['before' => ['keeper, admin']], function () {
        Route::get('keeper/reservations', ['uses' => 'ReservationController@searchAllReservations']);
        //Route::get('userlist', array('uses' => 'UserController@searchUsers'));
    });
    Route::group(['before' => 'manager|admin'], function () {
        Route::get('admin/reservationtest', ['uses' => 'ReservationController@test']);
        Route::get('admin', ['uses' => 'UserController@showAdmin']);

        Route::group(['before' => 'stats'], function () {
            // Statistics
            Route::get('admin/login_stats', ['uses' => 'AdminController@showStats']);
            Route::post('admin/login_stats', ['uses' => 'AdminController@postAdminSearchLogins']);
        });
        // Reservations
        Route::get('admin/reservations', ['uses' => 'ReservationController@showAllReservations']);

        // Users
        Route::post('admin/users/search', ['uses' => 'UserController@searchUsers']);
        Route::get('admin/users', ['uses' => 'UserController@showUsers']);
        Route::get('admin/users/edit/{id}', ['uses' => 'UserController@showEditUser']);
        Route::get('admin/users/delete/{id}', ['uses' => 'AdminController@deleteUser']);
        Route::post('admin/users/edit/delete', ['uses' => 'UserController@deleteRoleUser']);
        Route::post('admin/users/edit/{id}', ['uses' => 'UserController@addRoleUser']);
        Route::post('admin/users/activate', ['uses' => 'AdminController@activateUser']);
        Route::post('admin/users/changeclan', ['uses' => 'AdminController@changeClan']);
        Route::get('admin/users/add', ['uses' => 'AdminController@showAddUser']);
        Route::post('admin/users/add/sendnew', ['uses' => 'RemindersController@postRemindNewUser']);
        Route::get('admin/users/add/sendnew/{email}', ['uses' => 'RemindersController@postRemindNewUserManually']);
        Route::post('admin/users/add', ['uses' => 'AdminController@addUser']);
        Route::post('admin/users/addrole', ['uses' => 'RoleController@getRolesAjax']);

        // Roles
        Route::get('admin/roles', ['uses' => 'RoleController@showRoles']);
        Route::post('admin/roles', ['uses' => 'RoleController@searchRoles']);
        Route::get('admin/roles/edit/{id}', ['uses' => 'RoleController@showEditRole']);
        Route::post('admin/roles/edit/{id}', ['uses' => 'RoleController@saveRole']);
        Route::post('admin/roles/rights', ['uses' => 'RoleController@addRightRole']);
        Route::post('admin/roles/rights/delete', ['uses' => 'RoleController@deleteRightRole']);

        // Rights
        Route::get('admin/rights', ['uses' => 'RightController@showRights']);
        Route::post('admin/rights', ['uses' => 'RightController@searchRights']);
        Route::get('admin/rights/edit/{id}', ['uses' => 'RightController@showEditRight']);
        Route::post('admin/rights/edit/{id}', ['uses' => 'RightController@saveRight']);

        // Bills
        Route::get('admin/bills', ['uses' => 'BillController@showBills']);
        Route::get('admin/bill_list_print', ['uses' => 'BillController@showBillsPrint']);
        Route::get('admin/stats_list', ['uses' => 'StatsController@showStatsPrint']);
        Route::get('admin/bills/paid', ['uses' => 'BillController@payBill']);
        Route::get('admin/bills/unpaid', ['uses' => 'BillController@unPayBill']);
        Route::get('admin/bills/generate', ['uses' => 'BillController@generateBills']);

        Route::get('admin/settings', ['uses' => 'SettingController@showSettings']);
        Route::get('admin/settings/help', ['uses' => 'SettingController@getHelpSettings']);
        Route::post('admin/settings/help/add_topic', ['uses' => 'SettingController@addHelpTopic']);
        Route::post('admin/settings/help', ['uses' => 'SettingController@setHelpSettings']);
        Route::post('admin/settings', ['uses' => 'SettingController@setSettings']);
    });
    Route::group(['before' => 'admin'], function () {
        Route::get('periods', ['uses' => 'PeriodController@getPeriods']);
        Route::get('admin/calc', ['uses' => 'PeriodController@calculatePeriods']);
        Route::get('password/all', ['uses' => 'RemindersController@postRemindAll']);
        Route::get('password/failed', ['uses' => 'RemindersController@postRemindFailed']);
        Route::get('password/new/{pass}', 'RemindersController@manualPass');
        Route::get('app/is/maintenance', function () {
            touch(storage_path().'/meta/my.down');
        });
        Route::get('app/is/live', function () {
            @unlink(storage_path().'/meta/my.down');
        });
    });
    Route::get('user/profile/{id?}', ['uses' => 'UserController@showProfile']);
    Route::post('user/profile', ['uses' => 'UserController@saveProfile']);
    Route::post('user/profile/changepass', ['uses' => 'UserController@changePassword']);
    Route::get('user/reservation', ['uses' => 'ReservationController@getReservationsPerUser']);
});

Route::post('/', ['uses' => 'UserController@postLogin']);

Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
});
Route::post('password', ['uses' => 'RemindersController@postRemind']);
Route::get('password', 'RemindersController@getRemind');
Route::get('password/reset/{token}', 'RemindersController@getReset');
Route::post('password/reset/{token}', 'RemindersController@postReset');

Route::get('/cronjobs/reservations', 'ReservationController@cronReservation');
Route::get('/cronjobs/bills', 'BillController@cronBills');
Route::get('/cronjobs/birthdays', 'UserController@sendBirthdayMail');
