<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class Reservation extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'id',
        'reservation_started_at',
        'reservation_ended_at',
        'user_id',
        'period_id',
        'reservation_nights',
        'user_id_ab',
        'user_id_ab_name',
        'reservation_bill_sent',
        'reservation_reminder_sent',
        'reservation_reminder_sent',
        'reservation_reminder_sent_at',
        'reservation_title'
    );

    protected $setting;

    public function __construct()
    {
        $setting = \Illuminate\Support\Facades\App::make(Setting::class);
        $this->setting = $setting::getStaticSettings();
    }

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     *
     * @return mixed
     */
    public function guests() {
        return $this->hasMany('Guest')->select('guests.*');
    }

    /**
     *
     * @return mixed
     */
    public function bills() {
        return $this->belongsTo('Bill');
    }

    /**
     *
     * @return mixed
     */
    public function periods() {
        return $this->belongsTo('Period');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getReservationStartedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized('%d.%m.%Y');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getReservationEndedAtAttribute($value)
    {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized('%d.%m.%Y');
    }

    /**
     * @param $value
     * @param string $format
     * @return array|bool
     */
    public static function createDbDateFromInput ($value, $format = 'd.m.Y')
    {
        $valArray = [];
        $values = [];
        if (!is_array($value)) {
            $values[] = $value;
        } else {
            $values = $value;
        }
        foreach($values as $v) {
            $dateTime = DateTime::createFromFormat($format, $v);
            $errors = DateTime::getLastErrors();
            if (!empty($errors['warning_count']) || !empty($errors['error_count'])) {
                return null;
            }
            if ($dateTime !== false) {
                $valArray[] = \Carbon\Carbon::createFromFormat($format, $v)->setTime(0,0,0)->format('Y-m-d H:i:s');
            } else {
                return null;
            }
        }
        return $valArray;
    }

    /**
     * Gets reservations by period dates (start/end)
     *
     * @param      $periodID
     * @param bool $isJson
     * @return mixed
     */
    public function getReservationsPerPeriodV3 ($periodID, $isJson = true) {
        $periods =[];
        for ($j = $periodID - 1; $j < $periodID + 5; $j++) {
            $periods[] = $j;
        }
        $reservations = Reservation::whereIn('period_id', $periods)
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->select('reservations.id', 'user_id', 'user_id_ab', 'reservations.period_id', 'reservation_nights', 'reservation_started_at', 'reservation_ended_at', 'users.email', 'users.user_login_name')
            ->with(array(
                'guests' => function ($q) {
                    $q->select('guests.id', 'reservation_id', 'guest_number', 'guest_night', 'guest_ended_at', 'guest_started_at', 'role_id');
                },
            ))
            ->orderBy('reservation_started_at', 'asc')
            ->get();
        $reservations = self::setFreeBeds($reservations);
        if (!$isJson) {
            return [$reservations[0]->toArray(), $reservations[1]];
        }
        return [$reservations[0]->toJson(), $reservations[1]];
    }

    /**
     * by user ID
     * @param $start
     * @param $end
     * @param $uID
     * @return mixed
     */

    public function checkExistentReservationByUidV3($start, $end, $uID)
    {
        return self::where(function ($q) use ($start, $end, $uID) {
                $q->where('reservation_started_at', '<=', $start);
                $q->where('reservation_ended_at', '>=', $end);
                $q->where('user_id', '=', $uID);
            })
            ->orWhere(function ($q) use ($start, $end, $uID) {
                $q->where('reservation_started_at', '<=', $start);
                $q->where('reservation_ended_at', '<=', $end);
                $q->where('reservation_ended_at', '>=', $start);
                $q->where('user_id', '=', $uID);
            })
            ->orWhere(function ($q) use ($start, $end, $uID) {
                $q->where('reservation_started_at', '>=', $start);
                $q->where('reservation_started_at', '<=', $end);
                $q->where('reservation_ended_at', '<=', $end);
                $q->where('user_id', '=', $uID);
            })
            ->orWhere(function ($q) use ($start, $end, $uID) {
                $q->where('reservation_started_at', '>=', $start);
                $q->where('reservation_started_at', '<=', $end);
                $q->where('reservation_ended_at', '>=', $end);
                $q->where('user_id', '=', $uID);
            })
            ->first();
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    public function checkExistentReservationV3($start, $end)
    {
        return self::where(function ($q) use ($start, $end) {
                $q->where('reservation_started_at', '<=', $start);
                $q->where('reservation_ended_at', '>=', $end);
            })
            ->orWhere(function ($q) use ($start, $end) {
                $q->where('reservation_started_at', '<=', $start);
                $q->where('reservation_ended_at', '<=', $end);
                $q->where('reservation_ended_at', '>=', $start);
            })
            ->orWhere(function ($q) use ($start, $end) {
                $q->where('reservation_started_at', '>=', $start);
                $q->where('reservation_started_at', '<=', $end);
                $q->where('reservation_ended_at', '<=', $end);
            })
            ->orWhere(function ($q) use ($start, $end) {
                $q->where('reservation_started_at', '>=', $start);
                $q->where('reservation_started_at', '<=', $end);
                $q->where('reservation_ended_at', '>=', $end);
            })
            ->get();
    }

    /**
     * Get all reservations
     *
     * @return mixed Collection
     */
    public function getReservations () {
        $reservations = $this
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->select('users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
            //->where('user_id', '=', Auth::id())
            ->with(array('guests'=>function($query){
                $query
                    ->join('roles', 'roles.id', '=', 'guests.role_id')
                    ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
            }))
            ->get();
        $reservations->each(function ($r) {
            $s = new \DateTime(str_replace('_', '-', $r->reservation_started_at));
            $d = new \DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $r->reservation_started_at_show = $s->format(trans('formats.short-date-ts'));
            $r->reservation_ended_at_show = $d->format(trans('formats.short-date-ts'));
            $r->guests->each(function ($g) {
                $ss = new \DateTime(str_replace('_', '-', $g->guest_started_at));
                $dd = new \DateTime(str_replace('_', '-', $g->guest_ended_at));
                $g->guest_started_at_show = $ss->format(trans('formats.short-date-ts'));
                $g->guest_ended_at_show = $dd->format(trans('formats.short-date-ts'));
            });
        });
        return $reservations;
    }

    /**
     * @param       $year
     * @param array $family_code
     * @return array
     * @throws Exception
     */
    public function getReservationsStats ($year, $family_code = array('5', '7', '8', '9'))
    {
        if($year == NULL) {
            $year = array('%');
        }
        if(sizeof($family_code) == 0) {
            $fm = new Family();
            $fm_code = $fm->select('id')->get()->toArray();
            foreach ($fm_code as $fm) {
                $family_code[] = $fm['id'];
            }
        }

        $reservations = $this
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->join('clans', 'clans.id', '=', 'users.clan_id')
            ->join('families', 'families.id', '=', 'users.family_code')
            ->select(
                'users.user_first_name',
                'users.user_name',
                'users.clan_id',
                'clans.clan_description',
                'users.family_code',
                'families.family_description',
                'reservations.id',
                'reservations.reservation_nights',
                'reservations.reservation_started_at',
                'reservations.reservation_ended_at')
            ->where('reservations.reservation_started_at', 'like', $year[0])
            ->orWhere(function ($query) use($year) {
                foreach($year as $y){
                    $query->orWhere('reservations.reservation_started_at', 'like', $y);
                }
            })
            ->orderBy('reservations.reservation_started_at', 'asc')
            ->distinct()
            ->get();

        $res_totals = $this->getReservationsStatsPerMonthTotal($year);
        $reservations->family_sum = [];
        $reservations->guest_kind_sum = [];
        $reservations->guest_total_sum = [];
        $reservations->total_nights = [];
        $reservations->total_nights_sum = [];
        $reservations->total_family_nights_sum = [];
        $reservations->each(function ($r) use($family_code, $reservations, $res_totals) {
            $s = new \DateTime(str_replace('_', '-', $r->reservation_started_at));
            $d = new \DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $r->reservation_started_at_show = $s->format(trans('formats.short-date-ts'));
            $r->reservation_started_at_month = $s->format('n');
            $r->reservation_started_at_year = $s->format('Y');
            $r->reservation_ended_at_show = $d->format(trans('formats.short-date-ts'));
            $r->bill = Bill::where('reservation_id', '=', $r->id)->get();
            $r->guest = Guest::where('reservation_id', '=', $r->id)
                ->join('roles', 'roles.id', '=', 'guests.role_id')->get();
            foreach($r->guest as $guest) {
                $r->guest_sum += $guest->guest_number;
                if ($guest->role_id !== 5 && $guest->role_id !== 4) {
                    $r->guest_sum_adult_only += $guest->guest_number;
                }
                if(isset($reservations->guest_kind_sum[$r->reservation_started_at_year][$guest->role_description])) {
                    $reservations->guest_kind_sum[$r->reservation_started_at_year][$guest->role_description] += $guest->guest_number;
                } else {
                    $reservations->guest_kind_sum[$r->reservation_started_at_year][$guest->role_description] = $guest->guest_number;
                }
                if(isset($reservations->guest_total_sum[$r->reservation_started_at_year][$guest->role_description])) {
                    $reservations->guest_total_sum[$r->reservation_started_at_year][$guest->role_description] += $guest->guest_night;
                } else {
                    $reservations->guest_total_sum[$r->reservation_started_at_year][$guest->role_description] = $guest->guest_night;
                }
            }
            if(isset($reservations->family_sum[$r->reservation_started_at_year][$r->family_description])) {
                $reservations->family_sum[$r->reservation_started_at_year][$r->family_description] += 1;
            } else {
                $reservations->family_sum[$r->reservation_started_at_year][$r->family_description] = 1;
            }
        });
        $filtered_reservations = $reservations->filter(function($res) use($family_code) {
            return $res->family_code == $family_code[0] || $res->family_code == $family_code[1] || $res->family_code == $family_code[2] || $res->family_code == $family_code[3] ;
        });

        return array($filtered_reservations->toArray(), $reservations->family_sum, $reservations->guest_kind_sum, $reservations->guest_total_sum);
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getReservationsStatsCalendar ($year)
    {
        if($year == NULL) {
            $year = array('%');
        }
        $reservations = $this
            ->select(
                'reservations.id',
                'reservations.user_id',
                'reservations.reservation_nights',
                'reservations.reservation_started_at',
                'reservations.reservation_ended_at')

            ->where('reservations.reservation_started_at', 'like', $year[0])
            ->orWhere(function ($query) use($year) {
                foreach($year as $y){
                    $query->orWhere('reservations.reservation_started_at', 'like', $y);
                }
            })
            ->orderBy('reservations.reservation_started_at', 'asc')
            ->distinct()
            ->get();
        $reservations->each(function($r){
            $r->guest_number_total = 1;
            $r->number_nights_month = '';
            $r->guest = Guest::where('reservation_id', '=', $r->id)
                ->join('roles', 'roles.id', '=', 'guests.role_id')
                ->select('guest_number', 'guest_night', 'role_id')
                ->get();
            $r->guest->each(function($g) use($r){
                $r->guest_number_total += $g->guest_number;
            });
            $r->user = \User::where('users.id', '=', $r->user_id)
                ->join('families', 'families.id', '=', 'users.family_code')->get();
        });
        return $reservations;
    }

    /**
     * @param array $year
     * @return array
     */
    public function getReservationsStatsPerDayTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation->totals = [];
        $reservation->year_totals = [];
        $reservation->month_totals = [];
        $reservation->each(function($r) use($reservation){
            $startDate = new \DateTime(str_replace('_', '-', $r->reservation_started_at));
            $endDate = new \DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate);
            foreach($period as $p){
                $index = $p->format('Y_m_d');
                if(isset($reservation->totals[$index])) {
                    $reservation->totals[$index] +=  $r->guest_number_total;
                } else {
                    $reservation->totals[$index] =  $r->guest_number_total;
                }
                $index_month = $p->format('Y_m');
                if(isset($reservation->month_totals[$index_month])) {
                    $reservation->month_totals[$index_month] +=  $r->guest_number_total;
                } else {
                    $reservation->month_totals[$index_month] =  $r->guest_number_total;
                }
                $index_year = $p->format('Y');
                if(isset($reservation->year_totals[$index_year])) {
                    $reservation->year_totals[$index_year] +=  $r->guest_number_total;
                } else {
                    $reservation->year_totals[$index_year] =  $r->guest_number_total;
                }
            }
        });
        return array($reservation->totals, $reservation->month_totals, $reservation->year_totals);
    }

    /**
     * @param array $year
     * @return array
     */
    public function getReservationsStatsPerFamilyNightsTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation->totals = [];
        $reservation->familyProps = [];
        $reservation->years = [];
        $reservation->year_totals = [];
        $reservation->year_total = [];
        $reservation->each(function($r) use($reservation){
            $startDate = new DateTime(str_replace('_', '-', $r->reservation_started_at));
            $endDate = new DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate);
            foreach($period as $p){
                $index = $p->format('Y');
                if (isset($r->user[0]->family_description)) {
                    if(isset($reservation->totals[$index][$r->user[0]->family_description]) && $reservation->totals[$index][$r->user[0]->family_description] != '') {
                        $reservation->totals[$index][$r->user[0]->family_description] +=  $r->guest_number_total;
                    } else {
                        $reservation->totals[$index][$r->user[0]->family_description] =  $r->guest_number_total;
                    }
                    if (!in_array($r->user[0]->family_description, $reservation->familyProps)) {
                        $reservation->familyProps[] = $r->user[0]->family_description;
                    }
                    if(isset($reservation->year_total[$index])){
                        $reservation->year_total[$index] +=  $r->guest_number_total;
                    } else {
                        $reservation->year_total[$index] =  $r->guest_number_total;
                    }
                }
            }
        });
        return [$reservation->totals, $reservation->year_total, $reservation->familyProps];
    }

    /**
     * @param array $year
     * @return array
     */
    public function getReservationsStatsPerGuestNightsTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation->family = $this->getReservationsStatsPerFamilyNightsTotal($year);
        $reservation->year_totals = [];
        $reservation->guestProps = [];
        $reservation->totals_reservator = [];
        $reservation->totals = [];
        $reservation->totals_guest = [];
        $reservation->each(function($r) use($reservation){
            $startDate = new DateTime(str_replace('_', '-', $r->reservation_started_at));
            $endDate = new DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate);
            foreach($period as $p){
                $index = $p->format('Y');
                foreach($r->guest as $g){
                    $role = Role::where('id', $g->role_id)->select('role_description')->first();
                    if(isset($reservation->totals_guest[$index][$role->role_description])) {
                        $reservation->totals_guest[$index][$role->role_description] +=  $g->guest_number;
                    } else {
                        $reservation->totals_guest[$index][$role->role_description] =  $g->guest_number;
                    }
                    if(isset($reservation->year_totals[$index])) {
                        $reservation->year_totals[$index] +=  $g->guest_number;
                    } else {
                        $reservation->year_totals[$index] =  $g->guest_number;
                    }
                    if(isset($reservation->totals_reservator[$index]['Reservierender Benutzer'])){
                        $reservation->totals_reservator[$index]['Reservierender Benutzer'] = $reservation->family[1][$index] - $reservation->year_totals[$index];
                    }else{
                        $reservation->totals_reservator[$index]['Reservierender Benutzer'] = $reservation->family[1][$index] - $reservation->year_totals[$index];
                    }
                    if (!in_array($role->role_description, $reservation->guestProps)) {
                        $reservation->guestProps[] = $role->role_description;
                    }
                }
            }
        });
        return [
            'total_guests' => $reservation->totals_guest,
            'total_family' => $reservation->family,
            'total_year_guests' => $reservation->year_totals,
            'total_user' => $reservation->totals_reservator,
            'guest_props' => $reservation->guestProps
        ];
    }

    /**
     * @param array $year
     * @return array
     * @throws Exception
     */
    public function getReservationsStatsPerMonthTotal($year = array('2015-%'))
    {
        $res = $this->getReservationsStatsCalendar($year);
        $totals = [];
        $startDate = new \DateTime(str_replace('_', '-', $res[0]->reservation_started_at));
        $endDate = new \DateTime(str_replace('_', '-', $res[sizeof($res) - 1]->reservation_started_at));
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($startDate, $interval, $endDate);
        foreach($period as $p){
            $index = $p->format('Y_m_d');
            $indexMonth = $p->format('Y_m');
            $index_year = $p->format('Y');
            if(isset($res[$index])){
                if(isset($totals[$indexMonth])){
                    $totals[$indexMonth] += $res[$index];
                }else{
                    $totals[$indexMonth] = $res[$index];
                }
                if(isset($totals[$index_year])) {
                    $totals[$index_year] +=  $res[$index];
                } else {
                    $totals[$index_year] =  $res[$index];
                }
            }
        }
        return $totals;
    }

    /**
     * @param        $reservations
     * @param string $preFix
     * @param bool   $asJSON
     * @param string $format
     * @param bool   $withResID
     * @return mixed
     */
    public static function setFreeBeds($reservations, $preFix = 'freeBeds_', $asJSON = false, $format = 'Y_m_d', $withResID = true)
    {
        $ar = [];
        $cu = new User();
        $sums = [];
        $reservations->each(function ($r) use ($ar, $cu, $preFix, $format, $withResID, &$sums) {
            if (isset($r->user_id_ab) && !empty($r->user_id_ab)) {
                $cu = $cu->where('id', '=', $r->user_id_ab)->first();
                $r->user_id_ab_name = $cu->user_login_name;
            } else {
                $r->user_id_ab_name = '';
            }
            if (!is_null($r->guests)) {
                if ($r->guests->count() > 0) {
                    $r->guests->each(function  ($j) use ($r, $preFix, $format, $withResID, &$sums) {
                        $role_tax = Role::find($j->role_id);
                        $j->role_tax_night = $role_tax->role_tax_night;
                        $start = new DateTime(str_replace('_', '-', $j->guest_started_at));
                        $end = new DateTime(str_replace('_', '-', $j->guest_ended_at));
                        $checkEnd = new DateTime(str_replace('_', '-', $j->guest_ended_at));
                        $end->add(new DateInterval('P1D'));
                        $interval = new DateInterval('P1D');
                        $dateRange = new DatePeriod($start, $interval ,$end);
                        foreach($dateRange as $key => $date) {
                            $d = explode('_', $date->format($format));
                            $dd = intval($d[1]) - 1;
                            $d[1] = ($dd < 10) ? '0' . $dd : $dd;
                            if ($date < $checkEnd) {
                                if ($withResID && Auth::id() == $r->user_id) {
                                    if (array_key_exists($preFix . implode('_', $d) . 'resId' . $r->id, $sums)) {
                                        $sums[$preFix . implode('_', $d) . 'resId' . $r->id] += $j->guest_number;
                                    } else {
                                        $sums[$preFix . implode('_', $d) . 'resId' . $r->id] = $j->guest_number;
                                    }
                                    if (array_key_exists($preFix . implode('_', $d) . 'uID' . $r->user_id, $sums)) {
                                        $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] += $j->guest_number;
                                    } else {
                                        $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] = $j->guest_number;
                                    }
                                }
                                if (array_key_exists($preFix . implode('_', $d), $sums)) {
                                    $sums[$preFix . implode('_', $d)] += $j->guest_number;
                                } else {
                                    $sums[$preFix . implode('_', $d)] = $j->guest_number;
                                }
                            } else {

                                if ($withResID && Auth::id() == $r->user_id) {
                                    if (array_key_exists($preFix . implode('_', $d) . 'resId' . $r->id, $sums)) {
                                        $sums[$preFix . implode('_', $d) . 'resId' . $r->id] += 1;
                                    } else {
                                        $sums[$preFix . implode('_', $d) . 'resId' . $r->id] = 1;
                                    }
                                    if (array_key_exists($preFix . implode('_', $d) . 'uID' . $r->user_id, $sums)) {
                                        $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] += 1;
                                    } else {
                                        $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] = 1;
                                    }
                                    $sums[$preFix . implode('_', $d) . 'userID'] =  $r->user_id;
                                }
                                if (array_key_exists($preFix . implode('_', $d), $sums)) {
                                    $sums[$preFix . implode('_', $d)] += 1;
                                } else {
                                    $sums[$preFix . implode('_', $d)] = 1;
                                }

                            }
                            $sums[$preFix . implode('_', $d) . 'userID'] = $r->reservation_started_at . '|' . $r->reservation_ended_at . '|' . $r->user_id;
                            // Gastgeber
                            $sums[$preFix . implode('_', $d)] += 1;
                            if (array_key_exists($preFix . implode('_', $d) . 'resId' . $r->id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'resId' . $r->id] += 1;
                            }
                            if (array_key_exists($preFix . implode('_', $d) . 'uID' . $r->user_id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] += 1;
                            }
                        }
                    });
                }
            } else {
                $start = new DateTime(str_replace('_', '-', $r->reservation_started_at));
                $end = new DateTime(str_replace('_', '-', $r->reservation_ended_at));
                $checkEnd = new DateTime(str_replace('_', '-', $r->reservation_ended_at));
                $end->add(new DateInterval('P1D'));
                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod($start, $interval ,$end);
                foreach($dateRange as $key => $date) {
                    $d = explode('_', $date->format($format));
                    $dd = intval($d[1]) - 1;
                    $d[1] = ($dd < 10) ? '0' . $dd : $dd;
                    if ($date < $checkEnd) {
                        if ($withResID && Auth::id() == $r->user_id) {
                            if (array_key_exists($preFix . implode('_', $d) . 'resId' . $r->id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'resId' . $r->id] += 1;
                            } else {
                                $sums[$preFix . implode('_', $d) . 'resId' . $r->id] = 1;
                            }
                            if (array_key_exists($preFix . implode('_', $d) . 'uID' . $r->user_id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] += 1;
                            } else {
                                $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] = 1;
                            }
                            $sums[$preFix . implode('_', $d) . 'uID'] = $r->user_id;
                        }
                        if (array_key_exists($preFix . implode('_', $d), $sums)) {
                            $sums[$preFix . implode('_', $d)] += 1;
                        } else {
                            $sums[$preFix . implode('_', $d)] = 1;
                        }
                    } else {

                        if ($withResID && Auth::id() == $r->user_id) {
                            if (array_key_exists($preFix . implode('_', $d) . 'resId' . $r->id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'resId' . $r->id] += 1;
                            } else {
                                $sums[$preFix . implode('_', $d) . 'resId' . $r->id] = 1;
                            }
                            if (array_key_exists($preFix . implode('_', $d) . 'uID' . $r->user_id, $sums)) {
                                $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] += 1;
                            } else {
                                $sums[$preFix . implode('_', $d) . 'uID' . $r->user_id] = 1;
                            }
                        }
                        if (array_key_exists($preFix . implode('_', $d), $sums)) {
                            $r->{$preFix . implode('_', $d)} += 1;
                        } else {
                            $r->{$preFix . implode('_', $d)} = 1;
                        }

                    }
                    $sums[$preFix . implode('_', $d) . 'userID'] = $r->user_id;
                }

            }
        });

        if ($asJSON) {
            return [$reservations->toJson(), $sums];
        }
        return [$reservations, $sums];
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    protected function getOtherReservationsPerDate($start, $end)
    {
        $reservations = $this->checkExistentReservationV3($start, $end);
        $reservations->each(function ($res) {

        });
        return $reservations;
    }
}
