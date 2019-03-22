<?php

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, Notifiable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    protected $hidden = array(
        'password',
        'remember_token',
        'user_avatar',
        'deleted_at',
        'created_at',
        'updated_at',
        'user_stock_address',
        'user_stock_city',
        'user_stock_zip',
        'user_stock_country_code',
        'user_generation',
        'clan_id',
        'family_code',
        'id'
    );
    /**
     * If using soft delete, buggy in L4.2
     *
     * @var array
     */
     protected $dates = ['deleted_at'];

    /**
     * The attributes included to mass assignment.
     *
     * @var array
     */
    protected $fillable = array(
        'user_first_name',
        'user_name',
        'user_login_name',
        'email',
        'user_email2',
        'user_www',
        'user_www_label',
        'user_address',
        'user_city',
        'user_zip',
        'user_country_code',
        'user_stock_address',
        'user_stock_city',
        'user_stock_zip',
        'user_generation',
        'user_stock_country_code',
        'user_fon1',
        'clan_id',
        'user_fon1_label',
        'user_birthday',
        'user_avatar',
        'user_payment_method',
        'new_pass',
        'new_pass_confirmation',
         'clan_id',
        'family_code',
         'clan_code',
        'clan_description'
    );

    public static $searchColumns = array(
        'user_first_name',
        'user_name',
        'user_login_name',
        'email',
        'user_email2',
        'user_www',
        'user_www_label',
        'user_address',
        'user_city',
        'user_zip',
        'user_generation',
        'user_fon1',
        'user_fon1_label',
        'user_new'

    );
    /**
     * Default avatar
     *
     * @var string
     */
    public static $userAvatar = '/files/daniel_gasser/1412091350_running_man-512.png';

    /**
     *
     * @return mixed
     */
    public function roles(){
        return $this->belongsToMany('Role')->withTimeStamps();
    }

    /**
     *
     * @return mixed
     */
    public function clans(){
        return $this->belongsTo('Clan', 'clan_id');
    }

    /**
     *
     * @return mixed
     */
    public function generations(){
        return $this->belongsTo('Generation', 'generation_id');
    }

    /**
     *
     * @return mixed
     */
    public function families(){
        return $this->belongsTo('Family', 'family_code');
    }

    /**
     *
     * @return mixed
     */
    public function reservations(){
        return $this->hasMany('Reservation')->select('users.user_login_name', 'users.email');
    }

    /**
     *
     * @return mixed
     */
    public function login_stats(){
        return $this->hasMany('LoginStat', 'user_id');
    }

    /**
     *
     * @return mixed
     */
    public function posts(){
        return $this->hasMany('Post');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.language-name-ucfirst'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.long-date'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.short-date'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getUserLastLoginAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.short-date-time'));
    }

    public function getFillable() {
        return $this->fillable;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     *
     * @return mixed
     */
    public function getRoles() {
        return $this->find(Auth::id())->roles;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getRolesByID($id) {
        return self::find($id)->roles;
    }

    /**
     *
     * @return mixed
     */
    public function getUserClan(){
        return $this->find(Auth::id())->clan_id;
    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function getUserClanName($id) {
        return Clan::where('id', '=', $id)->select('clan_code', 'clan_description')->get();
    }

    /**
     * @return string
     */
    public function getCompleteName()
    {
        return $this->user_first_name . ' ' . $this->user_name;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->id;
    }

    /**
     *
     * @return bool
     */
    public static function isLoggedAdmin() {
        if (Auth::guest()) return 0;
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'ADMIN') return true;
        }
        return false;
    }

    /**
     *
     * @param $r role code
     * @return bool
     */
    public function getUsersRole($r) {
        $roles = self::find($this->id)->roles;
        foreach($roles as $role){
            if ($role->role_code == $r) return true;
        }
        return false;
    }
    /**
     *
     * @return bool
     */
    public static function isManager() {
        if (Auth::guest()) return 0;
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'VR') return true;
        }
        return false;
    }

    /**
     *
     * @return bool
     */
    public static function isReservator() {
        if (Auth::guest()) return 0;
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'BB' || $role->role_code == 'AB' || $role->role_code == 'GU') return true;
        }
        return false;
    }

    /**
     *
     * @return bool
     */
    public static function isClerk() {
        if (Auth::guest()) return 0;
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'BUHA') return true;
        }
        return false;
    }

    /**
     *
     * @return bool
     */
    public function isLoggedClerk() {
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'BUHA') return true;
        }
        return false;
    }

    /**
     *
     * @return bool
     */
    public static function isKeeper() {
        if (Auth::guest()) return 0;
        $roles = self::find(Auth::id())->roles;
        foreach($roles as $role){
            if ($role->role_code == 'KP') return true;
        }
        return false;
    }

    public function scopeCheckClan ($query, $clan)
    {
        if ($clan) {
            return $query->where('clan_id', '=', $clan);
        }
    }

    public function scopeCheckFamily ($query, $family)
    {
        if ($family) {
            return $query->where('family_code', '=', $family);
        }
    }

    public function scopeCheckUserRoles (\Illuminate\Database\Eloquent\Builder $query, $role)
    {
        if ($role) {
            return $query
                ->whereHas('roles', function ($q) use ($role) {
                    $q->where('id', $role);
                });
        }
    }



    /**
     * Search users by inputs
     *
     * @param $credentials
     * @return mixed
     */
    public function searchUser ($credentials) {
        $input = ($credentials['search_user'] == null) ? $input = '' : $credentials['search_user'];
        $clan = $credentials['search_clan'];
        $family = explode('|', $credentials['search_family'])[0];
        $role = ($credentials['search_role'] == '0') ? '' : $credentials['search_role'];

        $users = self::where(function ($q) use ($input, $clan) {
            foreach(self::$searchColumns as $s) {
                $q->orWhere($s, 'like', '%' . $input . '%');
            }
        })
            ->with('clans', 'families', 'roles')
            ->CheckClan($clan)
            ->CheckFamily($family)
            ->groupBy('users.id')
            ->whereHas('roles', function ($q) use($role) {
                if ($role == '') {
                    $q->where('roles.id', 'like', '%' . $role . '%');
                } else {
                    $q->where('roles.id', '=', $role);
                }
            })
        ->get();

        $users->each(function ($u) {
            $login = \LoginStat::where('user_id', '=', $u->getUserID())->orderBy('created_at', 'DESC')->skip(1)->first();
            if (is_object($login)) {
                $u->last_login = $login->created_at;
            } else {
                $u->last_login = '-';
            }
            $u->user_birthday = ($u->user_birthday !== '0000-00-00 00:00:00') ? date('d.m.Y', strtotime($u->user_birthday)) : '';
            $u->user_id = $u->getUserID();
            $u->country = DB::table('countries')
                ->select('country_name_' . trans('formats.langjs') . ' as country')
                ->where('country_code', '=', $u->user_country_code)
                ->first();
        });
        return $users;
    }

    /**
     * Destroys a user model permanently if it hasn't got any bills
     *
     */
    public function destroyUser () {
        $res = Reservation::where('user_id', '=', $this->id)->first();
        if ($res != null) {
            $bill = Bill::where('reservation_id', '=', $res->id)->first();
            if ($bill == null) {
                $this->reservations()->delete();
                $this->posts()->delete();
                $this->delete($this->id);

                return true;
            }
        }
        else {
            $this->login_stats()->delete();
            $this->destroy($this->id);
            return true;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $user = User::where('email', '=', \Illuminate\Support\Facades\Input::get('email'))->firstOrFail();
        $this->notify(new ResetPasswordNotification($token, $user));
    }
}
