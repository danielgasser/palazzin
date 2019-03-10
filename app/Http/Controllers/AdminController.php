<?php

namespace App\Http\Controllers;

use Clan;
use Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LoginStat;
use Role;
use Setting;
use User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Response;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 12.10.14
 * Time: 11:04
 */

class AdminController extends Controller
{

    /**
     * Get all logins
     *
     * @return mixed View
     */
    public function showStats()
    {
        setlocale(LC_ALL, Lang::get('formats.langlang'));
        $months = [];
        $years = [];
        $dt = date('Y-m-d', time());
        $dtt = date('Y-m-d', time());
        $set = Setting::getStaticSettings();


        $loginStats = new LoginStat();
        $loginStats = $loginStats->getLoginsByDate();
        foreach ($loginStats as $ls) {
            foreach ($ls->userDates as $val) {
                $f = new \DateTime($val['updated_at']);
                $months[\Carbon\Carbon::createFromFormat('Y-m-d', $f->format('Y-m-d'))->formatLocalized(trans('%m'))] = \Carbon\Carbon::createFromFormat('Y-m-d', $f->format('Y-m-d'))->formatLocalized(trans('%B'));
                $years[] = $f->format('Y');
            }
        }
        ksort($months);
        return view('logged.admin.stats')
            ->with('settings', $set)
            ->with('loginStats', $loginStats)
            ->with('todayMonthYearStart', \Carbon\Carbon::createFromFormat('Y-m-d', $dt)->formatLocalized(trans('formats.long-month-short-year')))
            ->with('todayMonthYearEnd', \Carbon\Carbon::createFromFormat('Y-m-d', $dtt)->formatLocalized(trans('formats.long-month-short-year')))
            ->with('calcTodayMonthYearStart', \Carbon\Carbon::createFromFormat('Y-m-d', $dt)->formatLocalized(trans('formats.calc-month-short-year')))
            ->with('calcTodayMonthYearEnd', \Carbon\Carbon::createFromFormat('Y-m-d', $dtt)->formatLocalized(trans('formats.calc-month-short-year')))
            ->with('monthNames', array_unique($months))
            ->with('startMonthSelected', \Carbon\Carbon::createFromFormat('Y-m-d', $dt)->formatLocalized(trans('%m')))
            ->with('endMonthSelected', \Carbon\Carbon::createFromFormat('Y-m-d', $dtt)->formatLocalized(trans('%m')))
            ->with('startYearSelected', \Carbon\Carbon::createFromFormat('Y-m-d', $dt)->formatLocalized(trans('%Y')))
            ->with('endYearSelected', \Carbon\Carbon::createFromFormat('Y-m-d', $dtt)->formatLocalized(trans('%Y')))
            ->with('years', array_unique($years));
    }

    /**
     * Search logins
     *
     * @return mixed json
     */
    public function postAdminSearchLogins()
    {
        Input::flash();
        setlocale(LC_ALL, trans('formats.langlang'));
        $loginStats = new LoginStat();
        $loginStats->getLoginsByDate(Input::except('_token'));
        $loginStats->filterLogins(Input::get('searchParams'));
        return Response::json($loginStats);
    }

    /**
     * Returns a view add user form
     *
     * @return mixed View
     */
    public function showAddUser()
    {
        $families = Family::select('id', 'clan_id', 'family_description')->get();
        $allFams = [];
        foreach ($families as $key => $f) {
            $allFams[$f->clan_id][$f->id] = $f->family_description;
        }
        return view('logged.admin.useradd')
            ->with('clans', [trans('dialog.select')] + Clan::pluck('clan_description', 'id')->toArray())
            ->with('allRoles', [trans('dialog.select')] + Role::getRoles())
            ->with('rolesAll', RoleController::getAllRolesAjax())
            ->with('families', $allFams)
            ->with('user', new User());
    }

    /**
     * Adds a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser(Request $request)
    {
        $input = $request->all();
        $addedRoles = [];
        $validator = Validator::make(
            $input,
            [
                'user_name' => 'required|min:3|alpha_dash',
                'user_first_name' => 'required|min:3|alpha_dash',
                'clan_id' => 'required|not_in:0',
                'user_family' => 'required|not_in:0',
                'email' => 'required|email|unique:users',
                'user_active' => 'required',
                'role_id_add' => 'required|not_in:0',
                'user_login_name' => 'unique:users|required|min:7|regex:/^[a-zA-Z\.]+$/',
            ],
            [
                'regex' => '<div class="messagebox">Format des Felds <span class="error-field"><span class="error-field">:attribute</span></span> ist ungültig:<br>
                            - Keine Umlaute<br>
                            - Keine Sonderzeichen<br>
                            Gültiges Format:
                            <b>vorname.name</b></div>',
                'not_in' => '<div class="messagebox">Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> darf nicht leer sein</div>'
            ]
        );
        if ($validator->fails()) {
            foreach (explode(',', $request->input('role_id_add')) as $key => $r) {
                $addedRoles[] = RoleController::getRolesAjax($r, false);
            }
            $request->flash();
            Session::put('addedRoles', $addedRoles);
            return back()->withErrors($validator->messages());
        }

        $user = \User::create($input);
        foreach (explode(',', $request->input('role_id_add')) as $r) {
            $user->roles()->attach($r);
        }
        $user->user_country_code = 41;
        $user->user_fon1_label = 'x';
        $user->user_active = $request->input('user_active');
        $user->save();
        $request = Request::create('admin/users/add/sendnew', 'POST');
        \Route::dispatch($request);
        return back()
            ->with('info_message', trans('errors.data-saved', ['a' => 'Neuer', 'data' => 'Benutzer']) . '.<br>' . trans('reminders.sent'));
    }

    /**
     * Deletes a user definitely
     *
     * @param $id User's id
     * @return mixed Redirect
     */
    public function deleteUser($id)
    {
        $user = \User::find($id);
        if ($user == null) {
            Session::put('error', trans('errors.notfound'));
            return redirect('userlist')->withErrors([trans('errors.notfound')]);
        }
        if ($user->getUsersRole('ADMIN') == 1) {
            Session::put('error', trans('errors.del-admin-user'));
            return redirect('userlist')->withErrors([trans('errors.del-admin-user')]);
        }
        if (Auth::id() == $user->id) {
            Session::put('error', trans('errors.del-selfy-user'));
            return redirect('userlist')->withErrors([trans('errors.del-selfy-user')]);
        }
        if ($user->destroyUser()) {
            Session::put('error', trans('errors.data-deleted', ['d' => 'Benutzer']));
            return redirect('admin/users')->with('info_message', trans('errors.data-deleted', ['d' => 'Benutzer']));
        }
        Session::put('error', trans('bill.bill-user-delete'));
        return redirect('userlist')->withErrors([trans('bill.bill-user-delete')]);
    }

    /**
     * Activates a user, allowing him/her to do reservations, bill, etc
     *
     * see model Role
     */
    public function activateUser()
    {
        $i = Input::except('_token');
        $user = \User::find($i['id']);
        $user->user_active = $i['userActive'];
        $user->save();
    }

    /**
     * Changes the clan of a user
     *
     */
    public function changeClan()
    {
        $i = Input::except('_token');
        $user = \User::find($i['id']);
        $user->clan_id = $i['clan_id'];
        $user->save();
    }

    public function manualPass($pass)
    {
        $password = \Hash::make($pass);
        \Tools::dd('neues pass: ' . $pass, false);
        \Tools::dd('neues pass: ' . $password, true);
    }

    public function postRemindNewUser()
    {
        $set = Setting::getStaticSettings();
        $credentials = ['email' => Input::get('email')];
        Config::set('auth.reminder.email', 'emails.auth.reminder_new');
        $response = Password::remind($credentials, function ($message, $c) use ($set) {
            $message->subject($set->setting_app_owner . ': ' . trans('reset.title'));
            $message->user = $c;
            $message->all = true;
        });
        switch ($response) {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::REMINDER_SENT:
                return redirect('/')->with('info_message', Lang::get($response));
        }
    }


}
