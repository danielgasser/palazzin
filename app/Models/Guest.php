<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class Guest extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'guests';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'guest_started_at',
        'guest_ended_at',
        'guest_guests',
        'guest_night',
        'guest_number',
        'role_id',
        'guest_title',
        'guest_tax',
        'guest_tax_role_id'
    );

    /**
     *
     * @return mixed
     */
    public function roles() {
        return $this->belongsTo('Role', 'id', 'role_id')->select('roles.*');
    }

    /**
     *
     * @return mixed
     */
    public function reservations() {
        return $this->belongsTo('Reservation');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getGuestStartedAtAttribute ($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized('%d.%m.%Y');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getGuestEndedAtAttribute ($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized('%d.%m.%Y');
    }

    /**
     * gets role-texts & calculate guest totals
     * @return $this Model
     */
    public function calcGuestSumTotals () {
        $this->rc = Role::where('id', '=', $this->role_id)->select('role_code as rc', 'role_tax_night')->first()->toArray();
        $this->role_code = trans('roles.' . $this->rc['rc']);
        $this->role_tax_night = $this->rc['role_tax_night'];
        $this->guestSum += $this->role_tax_night * $this->guest_night * $this->guest_number;
        unset($this->rc);
        return $this;
    }
}
