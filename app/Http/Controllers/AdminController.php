<?php

namespace App\Http\Controllers;

use Clan;
use Family;
use Illuminate\Http\Request;
use Role;
use User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
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
                'user_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
                'user_first_name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
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
        return back()
            ->with('info_message', trans('errors.data-saved', ['a' => 'Neuer', 'data' => 'Benutzer']));
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
     * creates a password at url password/new/{pass}
     *
     * @param $pass
     */
    public function manualPass($pass)
    {
        $password = \Hash::make($pass);
        \Tools::dd('neues pass: ' . $pass, false);
        \Tools::dd('neues pass: ' . $password, true);
    }
}
