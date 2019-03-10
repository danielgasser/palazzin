<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use User;
use Clan;
use Role;
use Family;


/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 04.10.14
 * Time: 14:44
 */

class UserController extends Controller
{

    /**
     * gets the aut. users id
     *
     * @return int users id
     */
    public function getAuthUserId()
    {
        return Auth::id();
    }

    /**
     * Authenticates a user after login
     *
     * @return mixed View/Redirect
     */
    public function postLogin()
    {
        $stay = Input::get('stay_tuned');
        $fieldToTake = filter_var(Input::get('user_login_name'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user_login_name';
        $credentials = [
            $fieldToTake => Input::get('user_login_name'),
            'password' => Input::get('password')

        ];
        $rules = [
            $fieldToTake => 'required',
            'password' => 'required'];

        $validator = Validator::make(
            $credentials,
            $rules
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors([trans('login.wrong')]);
        }
        $rememberMe = ($stay == 'on') ? true : false;
        if (Auth::attempt($credentials, $rememberMe) || Auth::viaRemember() && User::checkUsersOldWinBrowser() == 0) {
            $user = User::find(Auth::id());
            // If user is passive
            if ($user->user_active == 0) {
                Auth::logout();
                Session::put('error', trans('errors.passive'));
                return view('auth.login')
                    ->withErrors(trans('errors.passive'));
            }


            $time = new \DateTime();
            $time->format('Y-m-d H:m:s');
            $user->user_last_login = $time;
            $user->save();
            // Write login stats
            $loginStat = new \LoginStat();
            $loginStat->user_id = $user->id;
            $loginStat->save();
            if (User::isKeeper()) {
                return redirect('keeper/reservations');
            }
            if (User::isClerk()) {
                return redirect('admin/bills');
            }
            Session::put('lifetime', $time->format('Y-m-d H:m:s'));
            return Redirect::intended('home');
        }

        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::back()->withErrors($validator);
        }
        Session::put('error', trans('login.wrong'));
        return Redirect::back()->withErrors([trans('login.wrong')]);
    }

    public function showAdmin()
    {
        return view('logged.admin.home');
    }

    public function showProfile($id = null)
    {
        if ($id != null) {
            $user = User::find($id);
            $disabledForm = 'disabled';
            $requIred = ['', ''];
        } else {
            $user = User::find(Auth::id());
            $disabledForm = '';
            $requIred = ['requ', 'required'];
        }

        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        $countries = [];
        foreach ($cs as $c) {
            $countries[$c->country_code] = $c->country_name;
        }
        asort($countries);
        $clan = DB::table('clans')->where('id', '=', $user->clan_id)->select('id', 'clan_description', 'clan_code')->first();
        $families = DB::table('families')->where('id', '=', $user->family_code)->select('family_description')->first();
        /*
        foreach($families as $f){
            $chooseFam[$f->id] = $f->family_description;
        }
        */
        if (!is_null($families)) {
            $user->family_description = $families->family_description;
        } else {
            $user->family_description = '';
        }
        $settings = \Setting::getStaticSettings();
        $pm = $settings['setting_payment_methods'];
        $payMethods = [];
        foreach (explode(',', $pm) as $p) {
            $payMethods[$p] = trans('profile.' . $p);
        }
        if (isset($user->user_birthday)) {
            $user->user_birthday = \DateTime::createFromFormat('Y-m-d H:i:s', $user->user_birthday)->format('d.m.Y');
        }
        return view('user.profile')
            ->with('payment_method', $payMethods)
            ->with('info_message', 'blah_')
            ->with('user', $user)
            //->with('birthday', $birthday->format('d.m.Y'))
            ->with('countries', $countries)
            ->with('disabledForm', $disabledForm)
            ->with('requIred', $requIred)
            ->with('clan_desc', $clan->clan_description);
            //->with('families', $chooseFam);
    }

    /**
     * Saves the auth. users profile
     *
     * @return mixed Redirect
     */
    public function saveProfile()
    {
        $file = null;
        $new_mail_message = '';
        Input::flash();
        $user = User::find(Input::get('id'));
        $user_old_email = $user->email;
        if (Input::hasFile('user_avatar')) {
            $file = Input::file('user_avatar');
        }
        if (Input::get('id') != Auth::user()->id) {
            Session::put('error', '<div class="messagebox">Dies ist nicht Dein Profil</div>');
            return Redirect::back()->withErrors('<div class="messagebox">Dies ist nicht Dein Profil</div>');
        }
        $validator = Validator::make(
            Input::all(),
            [
                'user_first_name' => 'required|min:3|alpha',
                'user_name' => 'required|min:3|regex:/^[a-zA-Z\.\-\ ]+$/',
                'user_login_name' => 'required|regex:/^[a-zA-Z\.]+$/',
                'email' => 'required|email',
                'user_www_label' => 'max:50',
                'user_address' => 'required|min:3',
                'user_city' => 'required|min:3',
                'user_zip' => 'required|min:3',
                'user_country_code' => 'required',
                'user_fon1_label' => 'required',
                'user_fon1' => 'required|min:3',
                'user_payment_method' => 'required|not_in:0',
                'user_avatar' => 'image',
                'user_answer' => 'required|min:3',
                'user_question' => 'required|min:3',
            ],
            [
                'regex' => '<div class="messagebox">Format des Felds <span class="error-field"><span class="error-field">:attribute</span></span> ist ungültig:<br>
                            - Keine Umlaute<br>
                            - Keine Sonderzeichen<br>
                            Gültiges Format:
                            <b>vorname.name</b></div>',
                'not_in' => '<div class="messagebox">Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> darf nicht leer sein</div>',
                'boolean' => '<div class="messagebox">Dies ist nicht Dein Profil</div>'
            ]
        );
        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::back()->withErrors($validator);
        }
        if ($file != null) {
            $path = public_path() . '/files/' . str_replace('.', '_', $user->user_login_name);
            $fileName = str_replace('.', '_', $user->user_login_name) . '_avatar.' . $file->getClientOriginalExtension();
            $savePath = str_replace(public_path(), '', $path);
            $file->move($path, $fileName);
            $image = Image::make($path . '/' . $fileName);
            $image->resize(125, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save();
            $user->user_avatar = $savePath . '/' . $fileName;
        }
        if (!str_is($user_old_email, Input::get('email'))) {
            $new_mail_message = '<br>' . trans('errors.new_mail');
        }
        $fon1label = (strlen(Input::get('user_fon1_label')) <= 2) ? 'x' : ltrim(Input::get('user_fon1_label'), '#');
        $fon2label = (strlen(Input::get('user_fon2_label')) <= 2) ? 'x' : ltrim(Input::get('user_fon2_label'), '#');
        $fon3label = (strlen(Input::get('user_fon3_label')) <= 2) ? 'x' : ltrim(Input::get('user_fon3_label'), '#');
        $args = Input::except('user_fon1_label_show', 'user_fon2_label_show', 'user_fon3_label_show', 'user_avatar', 'user_birthday');
        $birthday = \DateTime::createFromFormat('d.m.Y', Input::get('user_birthday'));
        $user->user_birthday = $birthday->format('Y-m-d') . ' 00:00:00';
        $user->user_new = 0;
        $user->update($args);
        $user->user_fon1_label  = $fon1label;
        $user->user_fon2_label  = $fon2label;
        $user->user_fon3_label  = $fon3label;
        //$user->family_code = Input::get('user_family');

        $user->save();
        if ($user_old_email != $user->email) {
            Mail::send('emails.profile_change', ['id' => $user->id, 'old_email' => $user_old_email, 'email' => $user->email, 'login' => $user->user_login_name], function ($message) {
                $message->to(['software@daniel-gasser.com', 'luciana@vigano.ch'], 'Daniel Gasser')->subject('Palazzin: Profile Change');
            });
        }
        return Redirect::back()
            ->with('user', $user)
            ->with('info_message', trans('errors.data-saved', ['a' => 'Dein', 'data' => 'Profil']) . $new_mail_message);
    }

    /**
     * Gets a user by id
     *
     * @param $id User->id
     * @return mixed View
     */
    public function showEditUser($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            return Redirect::back();
        }
        $u = User::find($id);
        $clan = $u->getUserClanName($u->clan_id);
        $u->clan = $clan[0]->clan_description;
        $families = Family::select('id', 'clan_id', 'family_description')->get();
        $allFams = [];
        foreach ($families as $key => $f) {
            $allFams[$f->clan_id][$f->id] = $f->family_description;
        }
       // $countries = DB::table('countries')->lists('country_name_' . trans('formats.langjs'), 'id');
        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, id, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        //$countries = DB::table('countries')->lists('country_name_' . trans('formats.langjs'), 'country_code');
        foreach ($cs as $c) {
            $countries[$c->id] = $c->country_name;
        }
        asort($countries);
        $transRoles = [];
        $t = [];
        if (!$u->roles->isEmpty()) {
            foreach ($u->roles as $r) {
                $t[] = $r->role_code;
            }
        } else {
            $t[] = '';
        }
        $roles = DB::table('roles')
                ->select('role_code', 'id')
                ->whereNotIn('role_code', $t)
                ->whereNotIn('role_code', ['ADMIN'])
                ->where('role_guest', '=', 0)
                ->get();
        foreach ($roles as $role) {
            $transRoles[$role->id] = trans('roles.' . $role->role_code);
        }
        $clans = [
            Clan::pluck('clan_description', 'id')->toArray()
        ];
        array_unshift($clans[0] , trans('dialog.select'));
        return view('logged.admin.useredit')
            ->with('clans', $clans[0])
            ->with('user', $u)
            ->with('families', $allFams)
            ->with('countries', $countries)
            ->with('allRoles', $transRoles);
    }

    /**
     * Attaches a role to a user
     *
     * @param $id User->id
     * @return mixed Redirect
     */
    public function addRoleUser($id)
    {
        $i = Input::except('_token');
        $user = User::find($id);
        $r = DB::table('role_user')
            ->where('user_id', '=', $id)
            ->where('role_id', '=', $i['role_id'])
            ->count();
        if ($r > 0) {
            return back();
        }
        $user->roles()->attach($i['role_id']);
        return back();
    }

    /**
     * Detaches a role to a user
     *
     * @return mixed Redirect
     */
    public function deleteRoleUser()
    {
        $roleId = request()->input('role_id');
        $user = User::find(request()->input('user_id'));
        $role = Role::find(request()->input('role_id'));
        if (!is_object($user) || !is_object($role)) {
            return json_encode([]);
        }
        $validator = Validator::make(
            ['role_id' => $role->role_code . '|size:2'],
            ['size' => 'trans(\'errors.del-admin\')']
        );
        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::intended()->withErrors($validator);
        }
        if (is_object($user->roles())) {
            $user->roles()->detach($roleId);
        } elseif (is_object($role)) {
            $role->destroy();
        }
        $user->push();
        return json_encode([]);
    }

    public function activateUser ()
    {
        $uID = request()->input('user_id');
        $active = request()->input('user_active');
        $user = User::find($uID);
        if (is_object($user)) {
            $user->user_active = $active;
            $user->push();
        }
        return json_encode([]);
    }
    /**
     * Shows all users
     *
     * @return mixed
     */
    public function showUsers()
    {
        $users = $this->searchUsers();
        $clans = [
            Clan::pluck('clan_description', 'id')->toArray()
        ];
        $roles = [
            Role::where('role_guest', '=', '0')->pluck('role_description', 'id')->toArray()
        ];
        $families = [
            Family::select('family_description', DB::raw('CONCAT(id, "|", clan_id) AS fam_code'))
                ->pluck('family_description', 'fam_code')
                ->toArray()
        ];
        return view('logged.userlist')
            ->with('allUsers', $users)
            ->with('clans', [0 => trans('dialog.all')] + $clans[0])
            ->with('roleList', [0 => trans('dialog.all')] + $roles[0])
            ->with('families', [0 => trans('dialog.all')] + $families[0]);
    }

    public function userListPrint ()
    {
        $credentials = [
            'user_ids' => Input::get('uIDs'),
        ];
        $user = new User();
        $users = $user->whereIn('id', explode(',', $credentials['user_ids']))
            ->with('clans', 'families', 'roles')
            ->groupBy('users.id')
            ->get();
        $clans = [
            Clan::pluck('clan_description', 'id')->toArray()
        ];
        $roles = [
            Role::where('role_guest', '=', '0')->pluck('role_description', 'id')->toArray()
        ];
        $families = [
            Family::pluck('family_description', 'id')
                ->toArray()
        ];
        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        $countries = [];
        foreach ($cs as $c) {
            $countries[$c->country_code] = $c->country_name;
        }
        asort($countries);
        return view('logged.userlist-print')
            ->with('allUsers', $users)
            ->with('clans', $clans[0])
            ->with('countries', $countries)
            ->with('roleList', $roles[0])
            ->with('families', $families[0]);
    }

    /**
     * Searches a user by input
     *
     * @return mixed
     */
    public function searchUsers()
    {
        $user = new User();
        Input::flash();
        $sortby = (Input::get('sort_field') == null || strlen(Input::get('sort_field')) == 0) ? 'user_name' : Input::get('sort_field');
        $orderby = (Input::get('order_by') == null || strlen(Input::get('order_by')) == 0) ? 'ASC' : Input::get('order_by');
        $credentials = [
            'search_user' => Input::get('search_field'),
            'search_clan' => Input::get('clan'),
            'search_family' => Input::get('family'),
            'search_role' => Input::get('role'),
            'sort_field' => $sortby,
            'order_by' => $orderby
        ];
        $users = $user->searchUser($credentials);

        return json_encode($users);
    }

    /**
     *
     * @return mixed Redirect
     */
    public function changePassword()
    {
        $user = User::find(Auth::id());
        $validator = Validator::make(
            Input::except('old_pass'),
            [
                'new_pass' => ['required', 'regex:/([0-9@#$!?%A-Za-z])+/', 'confirmed'],
                'new_pass_confirmation' => 'required',
            ],
            [
                'regex' => '<div class="messagebox">Das Passwort muss mindestens<br>
                        - 10 Zeichen lang sein.<br>
                        - 1 Ziffer (0-9) enthalten.<br>
                        - 1 Grossbuchstaben (A-Z) enthalten.<br>
                        - 1 Kleinbuchstaben (a-z) enthalten.<br>
                        - 1 Sonderzeichen (%^?!&@#) enthalten</div>',
            ]
        );
        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::back()->withErrors($validator);
        }
        if (!Hash::check(Input::get('old_pass'), $user->password)) {
            Session::put('error', trans('errors.old_pass_false'));
            return Redirect::back()->withErrors([trans('errors.old_pass_false')]);
        }
        $user->password = Hash::make(Input::get('new_pass'));
        $user->save();
        Auth::logout();
        return redirect('/')->with('info_message', trans('errors.data-saved', ['a' => 'Dein', 'data' => ' neues Passwort']));
    }

    /**
     * Gets a user list opposite to the auth. users clan
     *
     * @return User
     */
    public function counterClanUsersList()
    {
        $user = new User();
        $user = $user->getSimpleUsersListByNotThisClan(Input::get('clan_id'));
        return $user;
    }
}
