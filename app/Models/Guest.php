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
        'guest_num',
        'guest_title',
        'guest_tax',
        'guest_tax_role_id'
    );

    /**
     *
     * @return mixed
     */
    public function roles() {
        return $this->belongsTo('Role')->select('roles.*');
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
     * Validates new Guest
     *
     * @param $inputs
     * @param $cp Period
     * @return array errors
     */
    public function validateFromReservation ($inputs, $cp) {
        $periodStartDate = new \DateTime($cp->period_start);
        $periodEndDate = new \DateTime($cp->period_end);
        $errors = [];
        if (intval($inputs['reservation_guest_num']) < 1 && $inputs['reservation_guest_guests'] != json_decode(Session::get('otherClanRoleId'))->id) {
            $errors[] = trans('reservation.warnings.guest_zero');
        }
        $guestRules = [
            'reservation_guest_started_at' => 'required|after:' . $periodStartDate->format('Y-m-d H:m:s'),
            'reservation_guest_ended_at' => 'required|before:' . $periodEndDate->format('Y-m-d H:m:s'),
            'reservation_guest_guests' => 'not_in:0',
            'reservation_guest_num' => 'required',
        ];
        $guestMessages = [
            'before' => '<div class="messagebox">' . trans('reservation.warnings.not_in_period') . '</div>',
            'after' => '<div class="messagebox">' . trans('reservation.warnings.not_in_period') . '</div>',
            'not_in' => '<div class="messagebox">' . trans('reservation.warnings.no_guest_kind') . '</div>',
        ];
        $beforeToday = Reservation::isReservationBeforeToday($inputs['reservation_guest_started_at'], $inputs['reservation_guest_ended_at']);
        if($beforeToday) {
            $errors[] = trans('reservation.warnings.before_today');
        }
        $guestValidator = Validator::make(
            $inputs,
            $guestRules,
            $guestMessages
        );

        if ($guestValidator->fails()) {
            foreach($guestValidator->messages() as $m) {
                $errors[] = $m;
            }
        }
        return $errors;
    }

    /**
     * Fills a Guest
     * @param $inputs
     */
    public function fillGuestFromReservation ($inputs) {
        unset($inputs['reservation_guest_sum_num_old']);
        unset($inputs['show_reservation_ended_at']);
        unset($inputs['show_reservation_started_at']);
        unset($inputs['sum_guests']);
        unset($inputs['before_today']);
        $this->guest_number = $inputs['reservation_guest_num'];
        $this->guest_started_at = $inputs['reservation_guest_started_at'];
        $this->guest_ended_at = $inputs['reservation_guest_ended_at'];
        $this->guest_night = $inputs['reservation_guest_nights'];
        $this->guest_tax = $inputs['reservation_guest_role_tax_night_real'];
        $this->guest_tax_role_id = $inputs['reservation_guest_guests'];
        $this->role_id = $inputs['reservation_guest_guests'];
        $this->reservation_id = $inputs['reserv_id'];
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

    /**
     * @param $startDate
     * @param $endDate
     * @return bool
     * @throws Exception
     */
    public static function countFreeBedsPerDate($startDate, $endDate) {
        $guest = new Guest();
        $set = Setting::getStaticSettings();
        $startDate->modify('-1 month');
        $stored = Session::get('localStorage');
        $endDate->modify('-1 month');
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($startDate, $interval, $endDate);
        //if (!Session::has('localStorage') || $stored == Null) return true;
        $start = $startDate->format('Y-m-d');
        foreach($daterange as $date) {
            $sd = $date->format('Y-m-d');
            if (is_null($stored)) {
                $res = DB::select(DB::raw("SELECT sum(guest_number) FROM guests where guest_started_at = '$start'"));
            } elseif (array_key_exists($date->format('Y-m-d'), $stored)) {
                //Tools::dd($stored, true);
                Tools::dd($stored[$date->format('Y-m-d')], false);
                Tools::dd($set->setting_num_bed, false);
                Tools::dd((intval($set->setting_num_bed)) - intval($stored[$date->format('Y-m-d')]), false);
                if ((intval($set->setting_num_bed)) - intval($stored[$date->format('Y-m-d')]) < 0) {
                    Session::forget('localStorage');
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validates, creates/saves guest(s) for a given reservation
     * @param $credentials
     * @param $currentPeriod
     * @param $reservation
     * @param $existentRes
     * @return mixed
     * @throws Exception
     */
    public static function createValidateGuestEntries ($credentials, $currentPeriod, $reservation, $existentRes) {
        $sumGuests = 0;
        $first = \Carbon\Carbon::createFromFormat('d.m.Y', $credentials['reservation_guest_started_at'][0])->formatLocalized('%Y-%m-%d');
        $last = \Carbon\Carbon::createFromFormat('d.m.Y', $credentials['reservation_guest_ended_at'][0])->formatLocalized('%Y-%m-%d');
        $localStorage = DB::table('local_storage')
            ->whereBetween('local_storage_date', array($first, $last))
            ->orderBy('local_storage_date', 'asc')
            ->get();
        $guestError = array();
        $startDate = new \DateTime($first);
        $endDate = new \DateTime($last);
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);
        foreach ($localStorage as $k => $ls) {
            foreach ($dateRange as $range) {
                if ($range->format('Y-m-d') . ' 00:00:00' == $ls->local_storage_date) {
                    if ((array_sum($credentials['reservation_guest_num']) + 1) > $ls->local_storage_number) {
                        $guestError[] = trans('dialog.texts.warning_no_free_beds');
                        return $guestError;
                    }
                }
            }
        }
        foreach($credentials['reservation_guest_started_at'] as $key => $value) {
            $guest = Guest::findOrNew($credentials['reserv_guest_id'][$key]);
            $start = explode('.', $value);
            $end = explode('.', $credentials['reservation_guest_ended_at'][$key]);
            if (!array_key_exists('reservation_guest_num', $credentials)) {
                $guestCredentials = [
                    'reserv_id' => $reservation->id,
                    'reservation_guest_started_at' => $start[2] . '-' . $start[1] . '-' . $start[0] . ' 00:00:00',
                    'reservation_guest_ended_at' => $end[2] . '-' . $end[1] . '-' . $end[0] . ' 00:00:00',
                    'reservation_guest_guests' => $credentials['reservation_guest_guests'][$key],
                    'reservation_guest_num' => 0,
                    'reservation_guest_nights' => 0,
                    'reservation_guest_role_tax_night_real' => (float)$credentials['reservation_guest_role_tax_night_real'][$key],
                    'sum_guests' => $sumGuests + 0,
                ];
            } else {
                $guestCredentials = [
                    'reserv_id' => $reservation->id,
                    'reservation_guest_started_at' => $start[2] . '-' . $start[1] . '-' . $start[0] . ' 00:00:00',
                    'reservation_guest_ended_at' => $end[2] . '-' . $end[1] . '-' . $end[0] . ' 00:00:00',
                    'reservation_guest_guests' => $credentials['reservation_guest_guests'][$key],
                    'reservation_guest_num' => $credentials['reservation_guest_num'][$key],
                    'reservation_guest_nights' => $credentials['reservation_guest_nights'][$key],
                    'reservation_guest_role_tax_night_real' => (float)$credentials['reservation_guest_role_tax_night_real'][$key],
                    'sum_guests' => $sumGuests + $credentials['reservation_guest_num'][$key],
                ];
                if (intval($credentials['reservation_guest_num'][$key]) < 1) {
                    if ($credentials['reservation_guest_guests'][$key] != json_decode(Session::get('otherClanRoleId'))->id) {
                        $guestError[] = trans('reservation.warnings.guest_zero');
                    }
                }
            }
            $sumGuests += $guestCredentials['reservation_guest_num'];
            $guestError = $guest->validateFromReservation($guestCredentials, $currentPeriod);
            if (sizeof($guestError) > 0) {
                if(!$existentRes) {
                    $reservation->destroy($reservation->id);
                }
                $guest->destroy($guest->id);
                Session::put('error', $guestError);
                // antsatt das:
                return Redirect::back()
                    ->withErrors($guestError);
            }
            if(!Guest::countFreeBedsPerDate($startDate, $endDate)) {
                if(!$existentRes) {
                    $reservation->destroy($reservation->id);
                }
                $guest->destroy($guest->id);
                Session::put('error', trans('dialog.texts.warning_no_free_beds'));
                return Redirect::back()
                    ->withErrors([trans('dialog.texts.warning_no_free_beds')]);
                //bis das
            }
            $guest->fillGuestFromReservation($guestCredentials);
            $reservation->guests()->save($guest);
        }
    }
}
