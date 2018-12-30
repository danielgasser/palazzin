<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'role_tax_annual',
        'role_tax_night',
        'role_tax_stock',
        'role_guest'
    );

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->belongsToMany('User')->withTimeStamps();
    }

    /**
     *
     * @return mixed
     */
    public function rights() {
        return $this->belongsToMany('Right')->withTimeStamps();
    }

    /**
     *
     * @return mixed
     */
    public function guest() {
        return $this->hasMany('Guest', 'role_id', 'id');
    }

    /**
     * Gets all roles
     *
     * @return array
     */
    public static function getRoles () {
        $roles = self::select('role_code', 'id')
            ->whereNotIn('role_code', ['ADMIN'])
            ->where('role_guest', '=', 0)
            ->whereNotIn('role_code', ['AB'])
            ->get();
        $allRoles = [];
        foreach($roles as $r) {
            $allRoles[$r->id] = trans('roles.' . $r->role_code);
        }
        return $allRoles;
    }

    /**
     * Gets roles for dorpdown
     *
     * @return mixed
     */
    public static function getRolesDropDown () {
        return self::select('id', 'role_code', 'role_tax_night')
            ->orWhere('role_guest', '=', 1)
            ->orWhere('role_code', '=', 'BB')
            ->orWhere('role_code', '=', 'GU')
            ->orderBy('role_tax_night', 'desc')
            ->get();
    }

    /**
     * Gets roles corresponding to clan
     *
     * @param $pid clan_id == user_id
     * @return array
     */
    public static function getRolesForGuest ($pid) {
        $rolesTrans[] = trans('dialog.select');
        if ($pid === true) {
            $roles = Role::select('id', 'role_code', 'role_tax_night')
                ->orWhere('role_guest', '=', 1)
                ->orWhere('role_code', '=', 'BB')
                ->orWhere('role_code', '=', 'AB')
                ->orWhere('role_code', '=', 'GU')
                ->orderBy('role_tax_night', 'desc')
                ->get();
        } else {
            $roles = Role::select('id', 'role_code', 'role_tax_night')
                ->orWhere('role_guest', '=', 1)
                ->orWhere('role_code', '=', 'BB')
                ->orWhere('role_code', '=', 'GU')
                ->orderBy('role_tax_night', 'desc')
                ->get();
        }
        foreach($roles as $role) {
            $rolesTrans[$role->id] = trans('roles.' . $role->role_code . '_short') . ' -> ' . trans('reservation.costs') . ': ' . $role->role_tax_night;
        }
        return $rolesTrans;
    }

    public static function getRolesTaxV3 ()
    {
        $arr = [];
        $roles = self::select('id', 'role_tax_night')
            ->orWhere('role_guest', '=', 1)
            ->orWhere('role_code', '=', 'BB')
            ->orWhere('role_code', '=', 'GU')
            ->get();
        foreach ($roles as $role) {
            $arr[$role->id] = $role->role_tax_night;
        }
        return json_encode($arr);
    }


    /**
     * Gets roles corresponding to clan
     *
     * @param $pid clan_id == user_id
     * @return array
     */
    public static function getRolesForGuestV3 ($pid) {
        $rolesTrans[] = trans('dialog.select');
        if ($pid === true) {
            $roles = Role::select('id', 'role_code', 'role_tax_night')
                ->orWhere('role_guest', '=', 1)
                ->orWhere('role_code', '=', 'BB')
                ->orWhere('role_code', '=', 'AB')
                ->orWhere('role_code', '=', 'GU')
                ->orderBy('role_tax_night', 'desc')
                ->get();
        } else {
            $roles = Role::select('id', 'role_code', 'role_tax_night')
                ->orWhere('role_guest', '=', 1)
                ->orWhere('role_code', '=', 'BB')
                ->orWhere('role_code', '=', 'GU')
                ->orderBy('role_tax_night', 'desc')
                ->get();
        }
        foreach($roles as $role) {
            $rolesTrans[$role->id] = trans('roles.' . $role->role_code . '_short');
        }
        return $rolesTrans;
    }


    /**
     * Get guest roles exclusively
     *
     * @return mixed
     */
    public static function getRolesForGuestOnly () {
        return Role::select('id', 'role_code', 'role_tax_night')
            ->orWhere('role_guest', '=', 1)
            ->orWhere('role_code', '=', 'BB')
            ->orWhere('role_code', '=', 'AB')
            ->orWhere('role_code', '=', 'GU')
            ->orderBy('role_tax_night', 'desc')
            ->get();
    }



    public static function getRoleByRoleCode($roleCode)
    {
        return Role::where('role_code', '=', $roleCode)->first()->toArray();
    }
}
