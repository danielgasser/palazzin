<?php

namespace App\Http\Controllers;

use App\Libraries\Tools;
use Period;
use Role;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Created by PhpStorm.
 * User: pc-shooter
 * Date: 02.12.14
 * Time: 11:22
 */

class RoleController extends Controller
{

    /**
     * Gets all roles
     *
     * @return mixed View
     */
    public function showRoles()
    {
        return view('logged.admin.roles')
            ->with('allRoles', Role::all());
    }

    /**
     * Gets all roles AJAX
     *
     * @param $id
     * @param $json
     * @return string json
     */
    public static function getRolesAjax($id = null, $json = true)
    {
        if (is_null($id)) {
            $id = Input::get('id');
        }
        if (is_null($id)) {
            return json_encode([]);
        }
        $role = Role::find($id);
        $defRoles = [];
        $defRoles['id'] = $role->id;
        $defRoles['role_c'] = $role->role_code;
        $defRoles['role_code'] = trans('roles.' . $role->role_code);
        $defRoles['role_tax_annual'] = $role->role_tax_annual;
        $defRoles['role_tax_night'] = $role->role_tax_night;
        $defRoles['role_tax_stock'] = $role->role_tax_stock;
        $defRoles['role_guest'] = $role->role_guest;
        foreach ($role->rights as $r) {
            $defRoles['role_rights'][] = trans('rights.' . $r->right_code);
        }
        if (!$json) {
            return $defRoles;
        }
        return json_encode($defRoles);
    }

    /**
     * Gets a role by id
     *
     * @param $id Role->id
     * @return mixed View
     */
    public function showEditRole($id)
    {
        $role = Role::find($id);
        $t = [];
        if (!$role->rights->isEmpty()) {
            foreach ($role->rights as $r) {
                $t[] = $r->right_code;
            }
            $rights = DB::table('rights')
                ->select('right_code', 'id')
                ->whereNotIn('right_code', $t)
                ->get();
            $allRights = [];
            foreach ($rights as $right) {
                $allRights[$right->id] = trans('rights.' . $right->right_code);
            }
        } else {
            $allRights = [];
        }
        return view('logged.admin.roleedit')
            ->with('role', $role)
            ->with('allRoles', Role::all())
            ->with('allRights', $allRights);
    }

    /**
     * Saves an existing role
     *
     * @param $id Role->id
     * @return mixed Redirect
     */
    public function saveRole($id)
    {
        $i = Input::except('_token');
        $data = [
            'role_tax_annual' => $i['role_tax_annual'],
            'role_tax_night' => $i['role_tax_night'],
            'role_guest' => $i['role_guest']
        ];
        $rules = [
            'role_tax_annual' => 'required|numeric',
            'role_tax_night' => 'required|numeric',
            'role_guest' => 'required|numeric'
        ];
        $validator = Validator::make(
            $data,
            $rules
        );
        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::back()->withErrors($validator);
        }
        $role = Role::find($id);
        $role->update($i);
        return redirect('admin/roles')
            ->with('info_message', trans('errors.data-saved', ['a' => 'Die', 'data' => 'Rolle']));
    }

    /**
     * Searches roles by input
     *
     * @return mixed View
     */
    public function searchRoles($in_put)
    {
        $input = (!isset($in_put)) ? Input::get('searchAllRoles') : $in_put;
        $roles = Role::where('role_code', 'LIKE', '%' . $input . '%')
            ->orWhere('role_description', 'LIKE', '%' . $input . '%')
            ->orWhere('role_tax_annual', 'LIKE', '%' . $input . '%')
            ->orWhere('role_tax_night', 'LIKE', '%' . $input . '%')
            ->orWhere('role_tax_stock', 'LIKE', '%' . $input . '%')
            ->orWhere('role_guest', 'LIKE', '%' . $input . '%')
            ->get();
        if ($roles->isEmpty()) {
            return view('logged.admin.roles')
                ->with('allRoles', Role::all());
        }
        return view('logged.admin.roles')
            ->with('allRoles', $roles);
    }

    /**
     * Attaches a right to a role & saves it
     *
     * @return mixed Redirect empty
     */
    public function addRightRole()
    {
        $i = Input::except('_token');
        $role = Role::find($i['role_id']);
        $r = DB::table('right_role')
            ->where('right_id', '=', $i['right_id'])
            ->where('role_id', '=', $i['role_id'])
            ->count();
        if ($r > 0) {
            return Redirect::back();
        }
        $role->rights()->attach($i['right_id']);
        return Redirect::back();
    }

    /**
     * Detaches a right form a role & destroys it
     *
     * @return mixed Redirect empty
     */
    public function deleteRightRole()
    {
        $i = Input::except('_token');
        $role = Role::find($i['role_id']);
        $role->rights()->detach($i['right_id']);
        return Redirect::back();
    }

    /**
     * Dropdown roles
     *
     * @return mixed json
     */
    public function roleList()
    {
        $p = Period::find(Input::get('period_id'));

        $roles = Role::getRolesForGuest(($p->clan_id == Auth::user()->clan_id));
        Tools::dd($roles, false);
        return Response::json($roles);
    }

    /**
     * Gets the opposite roles for dropdown that the clan of auth. user
     *
     * @return mixed json
     */
    public function getRoleForeignClan()
    {
        $p = Period::find(Input::get('period_id'));
        $roles = Role::getRolesForGuest(($p->clan_id == Auth::user()->clan_id));
        return Response::json($roles);
    }

    public function getPriceList()
    {
        $roles = Role::where('role_code', 'like', '%G%')
            ->orWhere('role_code', '=', 'AG')
            ->orWhere('role_code', '=', 'BB')
            ->select('role_tax_annual', 'role_tax_night', 'role_tax_stock', 'role_code', 'role_description')
            ->get();
        return view('logged.prices')
            ->with('allRoles', $roles);
    }
}
