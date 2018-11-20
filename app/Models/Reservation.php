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
        'reservation_reminder_sent_at'
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
    public function createDbDateFromInput ($value, $format = 'd.m.Y')
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
                return false;
            }
            if ($dateTime !== false) {
                $valArray[] = \Carbon\Carbon::createFromFormat($format, $v)->setTime(0,0,0)->format('Y-m-d H:i:s');
            } else {
                return false;
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
            return $reservations->toArray();
        }
        return $reservations->toJson();
    }

    /**
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
    public function checkExistentReservationByDateV3($start, $end)
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
            ->first();
    }

    /**
     * Get all reservations
     *
     * @return mixed Collection
     */
    public function getReservations () {
        $isUserRoute = strpos(Route::getCurrentRoute()->getPath(), 'user');
        if ($isUserRoute === false) {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->with('users')
                ->get();
        } else {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->where('user_id', '=', Auth::id())
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->get();
        }
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

    public function getCountReservations ()
    {
        $isUserRoute = strpos(Route::getCurrentRoute()->getPath(), 'user');
        if ($isUserRoute === false) {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->with('users')
                ->count();
        } else {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->where('user_id', '=', Auth::id())
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->count();
        }
        return $reservations;
    }

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
        $reservations->family_sum = array();
        $reservations->guest_kind_sum = array();
        $reservations->guest_total_sum = array();
        $reservations->total_nights = array();
        $reservations->total_nights_sum = array();
        $reservations->total_family_nights_sum = array();
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

    public function getReservationsStatsPerDayTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation->totals = array();
        $reservation->year_totals = array();
        $reservation->month_totals = array();
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

    public function getReservationsStatsPerFamilyNightsTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation->totals = array();
        $reservation->years = array();
        $reservation->year_totals = array();
        $reservation->each(function($r) use($reservation){
            $startDate = new \DateTime(str_replace('_', '-', $r->reservation_started_at));
            $endDate = new \DateTime(str_replace('_', '-', $r->reservation_ended_at));
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
                    if(isset($reservation->year_total[$index])){
                        $reservation->year_total[$index] +=  $r->guest_number_total;
                    } else {
                        $reservation->year_total[$index] =  $r->guest_number_total;
                    }
                }
            }
        });
        return array($reservation->totals, $reservation->year_total);
    }

    public function getReservationsStatsPerGuestNightsTotal($year = array('2015-%'))
    {
        $reservation = $this->getReservationsStatsCalendar($year);
        $reservation_totals = $this->getReservationsStatsPerFamilyNightsTotal($year);
        $reservation->year_totals = array();
        $reservation->totals_reservator = array();
        $reservation->each(function($r) use($reservation, $reservation_totals){
            $startDate = new \DateTime(str_replace('_', '-', $r->reservation_started_at));
            $endDate = new \DateTime(str_replace('_', '-', $r->reservation_ended_at));
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($startDate, $interval, $endDate);
            foreach($period as $p){
                $index = $p->format('Y');
                foreach($r->guest as $g){
                    $role = Role::where('id', $g->role_id)->select('role_description')->first();
                    if(isset($reservation->totals[$index][$role->role_description])) {
                        $reservation->totals[$index][$role->role_description] +=  $g->guest_number;
                    } else {
                        $reservation->totals[$index][$role->role_description] =  $g->guest_number;
                    }
                    if(isset($reservation->year_totals[$index])) {
                        $reservation->year_totals[$index] +=  $g->guest_number;
                    } else {
                        $reservation->year_totals[$index] =  $g->guest_number;
                    }
                    if(isset($reservation->totals_reservator[$index]['Reservierender Benutzer'])){
                        $reservation->totals_reservator[$index]['Reservierender Benutzer'] = $reservation_totals[1][$index] - $reservation->year_totals[$index];
                    }else{
                        $reservation->totals_reservator[$index]['Reservierender Benutzer'] = $reservation_totals[1][$index] - $reservation->year_totals[$index];
                    }
                }
            }
        });
        return array($reservation->totals, $reservation->year_totals, $reservation->totals_reservator);
    }

    public function getReservationsStatsPerMonthTotal($year = array('2015-%'))
    {
        $res = $this->getReservationsStatsCalendar($year);
        $totals = array();
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

    public function getReservationsByUser($id)
    {
        return $this->select('reservations.*')->with('guests')
            ->where('user_id', '=', $id)
            ->with(array('guests'=>function($query){
                $query
                    ->join('roles', 'roles.id', '=', 'guests.role_id')
                    ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
            }))
            ->get();
    }

    /**
     * Get reservations by input->$opts
     *
     * @param $opts
     * @return mixed Collection
     */
    public function getReservationsAjax ($opts) {
        if (!isset($opts['start']) || strlen($opts['start'] == 0)) {
            $d = new \DateTime($this->setting['setting_calendar_start']);
            $startD = $d->format('Y-m-d');
            $d->modify('+' . $this->setting['setting_calendar_duration'] . ' year');
            $endD = $d->format('Y-m-d');
        } else {
            $d = new \DateTime($opts['start']);
            $startD = $d->format('Y-m-d');
            $d->modify('+1 month');
            $endD = $d->format('Y-m-d');
        }
        if ($opts['monthyear'] != '') {
            $my = '%' . $opts['monthyear'] . '%';
        } else {
            $my = '%%';
        }
        $searchField = (isset($opts['search_field'])) ? $opts['search_field'] : '';
        $sortField = (isset($opts['sort_field'])) ? $opts['sort_field'] : 'reservation_started_at';
        $orderby = (isset($opts['sort_field'])) ? $opts['order_by'] : 'ASC';
        if(empty($searchField)) {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('reservations.user_id', 'users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->whereBetween('reservation_started_at', [$startD, $endD])
                ->where('reservations.reservation_started_at', 'LIKE', $my)
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->orderBy($sortField, $orderby)
                ->get();
        } else {
            $reservations = $this
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('reservations.user_id', 'users.user_first_name', 'users.user_name', 'reservations.*')->with('guests')
                ->where('reservations.user_id', '=', $searchField)
                ->whereBetween('reservation_started_at', [$startD, $endD])
                ->where('reservations.reservation_started_at', 'LIKE', $my)
                ->with(array('guests'=>function($query){
                    $query
                        ->join('roles', 'roles.id', '=', 'guests.role_id')
                        ->select('guests.*', 'roles.id','roles.role_code', 'roles.role_tax_night');
                }))
                ->orderBy($sortField, $orderby)
                ->get();

        }
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
     * Gets auth. users reservations
     *
     * @return array
     */
    public function getAuthReservationsArrayJS () {
        $userReservations = [];
        $userRes = $this->where('user_id', '=', Auth::id())
            ->select('id', 'reservation_started_at', 'reservation_ended_at')
            ->orderBy('reservation_started_at', 'asc')
            ->with('guests')
            ->get()
            ->toArray();
        foreach($userRes as $key => $val) {
            $sf = \Carbon\Carbon::createFromFormat('Y_m_d', $val['reservation_started_at'])->formatLocalized(trans('formats.long-date-user-res-js'));
            $ef = \Carbon\Carbon::createFromFormat('Y_m_d', $val['reservation_ended_at'])->formatLocalized(trans('formats.long-date-user-res-js'));
            $sm = \Carbon\Carbon::createFromFormat('Y_m_d', $val['reservation_started_at'])->formatLocalized('%m');
            $sy = \Carbon\Carbon::createFromFormat('Y_m_d', $val['reservation_started_at'])->formatLocalized('%Y');
            $sm = intval($sm) - 1;
            $m = $sm . '_' . $sy;
            $guestStr = [];
            foreach($val['guests'] as $ky => $v) {
                if ($v['guest_number'] == '0') {
                    $guestNum = trans('reservation.guest_many_no_js.none');
                } elseif($v['guest_number'] == '1') {
                    $guestNum = trans('reservation.guest_many_no_js.one');
                } else {
                    $guestNum = trans('reservation.guest_many_no_js.mores');
                }
                $guestStr[] = $v['guest_number'] . ' ' . $guestNum;
            }
            $userReservations['xxx'] = trans('reservation.your_title');
            $with = (sizeof($guestStr) > 0) ? trans('dialog.with', ['m' => 'm']) : '';
            $userReservations[$val['id'] . '|' . $m] = $sf . ' - ' . $ef . ' ' . $with . ' ' . implode('/ ', $guestStr);
        }
        if (sizeof($userRes) == 0) {
            $userReservations[0] = trans('reservation.no_bookings');
        }
        return $userReservations;
    }

    /**
     * Gets reservations by id with guests/roles
     *
     * @param $id
     * @return mixed Collection
     */
    public function getReservationByIdWithGuestAndRole ($id) {
       $r = self::find($id)
           ->with(array(
               'guests' => function ($q) {
                   $q->select('guests.id', 'reservation_id', 'guest_number', 'guest_night', 'guest_ended_at', 'guest_started_at', 'role_id');
               },
           ))->first();
        $r->guests->each(function ($g) use($r) {
            $g->role_code = Role::where('id', '=', $g->role_id)->select('role_code', 'role_tax_night')->first()->toArray();
            $g->guestSum += $g['role_code']['role_tax_night'] * $g->guest_night * $g->guest_number;
            $r->reservationSum += $g->guestSum;
        });
        return $r;
    }

    /**
     * Gets reservations by id & date with guests/roles
     *
     * @param null $id
     * @param bool $isJson
     * @return mixed Collection|json
     */
    public function getReservationsPerDateById ($id = null, $isJson = false) {
        if ($id != null) {
            $reservations = Reservation::where('id', '=', $id)->get();
        } else {
            $reservations = Reservation::whereBetween('reservation_started_at', [Input::get('this_month'), Input::get('next_month')])
                ->join('users', 'users.id', '=', 'reservations.user_id')
                ->select('reservations.id', 'user_id', 'user_id_ab', 'period_id', 'reservation_nights', 'reservation_started_at', 'reservation_ended_at', 'users.email', 'users.user_login_name')
                ->with(array(
                    'guests' => function ($q) {
                        $q->select('id', 'reservation_id', 'guest_number', 'guest_night', 'guest_ended_at', 'guest_started_at', 'role_id');
                    },
                ))
                ->get();
        }
        $ar = [];
        $cu = new User();
        $reservations->each(function ($i) use ($ar, $cu) {
            if (isset($i->user_id_ab) && !empty($i->user_id_ab)) {
                $cu = $cu->where('id', '=', $i->user_id_ab)->first();
                $i->user_id_ab_name = $cu->user_login_name;
            } else {
                $i->user_id_ab_name = '';
            }
            $i->guests->each(function  ($j) use ($i, $ar) {
                $role_tax = Role::find($j->role_id);
                $j->role_tax_night = $role_tax->role_tax_night;
                $start = new \DateTime(str_replace('_', '-', $j->guest_started_at));
                $end = new \DateTime(str_replace('_', '-', $j->guest_ended_at));
                $end->add(new DateInterval('P1D'));
                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod($start, $interval ,$end);
                foreach($dateRange as $key => $date) {
                    $d = explode('_', $date->format('Y_m_d'));
                    $dd = intval($d[1]) - 1;
                    $d[1] = ($dd < 10) ? '0' . $dd : $dd;
                    $i->{implode('_', $d)} += $j->guest_number;
                    $ar[$date->format('Y_m_d')] = $i->{$date->format('Y_m_d')};
                }
            });
        });
        if (!$isJson) {
            return $reservations->toJson();
        }
        return $reservations;
    }

    /**
     * Gets future reservations and sends mail to user and/or housekeeper
     * @param $sendToHousekeeper
     * @param $set
     * @return array
     */
    public function getFutureReservations ($sendToHousekeeper, $set) {
        $today = new \DateTime();
        $tomorrow = new \DateTime();
        $tomorrow->modify('+' . $set->setting_reminder_days . ' day');
        $today->setTime(0, 0, 0);
        $tomorrow->setTime(0, 0, 0);
        $data = [];
        $res = self::whereBetween('reservation_started_at', [$today->format('Y-m-d') . ' 00:00:00', $tomorrow->format('Y-m-d') . ' 00:00:00'])
            ->where('reservation_reminder_sent', '=', '0')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->join('guests', 'guests.reservation_id', '=', 'reservations.id')
            ->select('guests.guest_number', 'users.email', 'users.user_fon1', 'users.user_first_name', 'users.user_name', 'reservations.*')
            ->get();
        $counter = 0;
        $res->each(function ($r) use(&$data, $set, &$counter, $today)  {

            $data[$counter]['address'] = $r->user_first_name . ' ' . $r->user_name;
            $data[$counter]['message_text'] = $set->setting_start_reservation_mail_text;
            $data[$counter]['to'] = $r->email;
            $data[$counter]['guests'] = $r->guest_number;
            $data[$counter]['fon'] = $r->user_fon1;
            $data[$counter]['from'] = \Carbon\Carbon::createFromFormat('Y_m_d', $r->reservation_started_at)->formatLocalized(trans('formats.long-date-no-time'));
            $data[$counter]['till'] = \Carbon\Carbon::createFromFormat('Y_m_d', $r->reservation_ended_at)->formatLocalized(trans('formats.long-date-no-time'));
            $counter++;
            $r->reservation_reminder_sent = 1;
            $r->reservation_reminder_sent_at = $today->format('Y-m-d') . ' 00:00:00';
            $r->push();
        });
        $errors = array();
        if ($sendToHousekeeper && $res->count() > 0) {
            $houseKeeper = \User::whereHas('roles', function ($q) {
                $q->where('role_code', '=', 'KP');
            })
                ->get();
            $houseKeeper->each(function($h) use(&$data, $set, $errors) {
                //Tools::dd($data);
                Mail::send('emails.reservation_reminder_housekeeper', ['data' => $data] + ['address_h' => $h->user_name] + ['settings' => $set], function ($message) use ($h, $set) {
                    $message->from($set->setting_app_owner_email, $set->setting_app_owner);
                    $message->to($h->email)->subject($set->setting_app_owner . ': ' . trans('reservation.begin_res_housekeeper', ['z' => '']));
                });
                $errors[]['mail_errors'] = Mail::failures();
            });
        }
        foreach($data as $r) {
            Mail::send('emails.reservation_reminder', ['res' => $r] + ['settings' => $set], function ($message) use($r, $set) {
                $message->from($set['setting_app_owner_email'], $set['setting_app_owner']);
                $message->to($r['to'])->subject($set['setting_app_owner'] . ': ' . trans('reservation.begin_res'));
            });
            $errors[]['mail_errors'] = Mail::failures();
        }
        $data['errors'] = $errors;
        return $data;
    }

    /**
     * Checks if a reservation has started/ended before today
     *
     * @param $start
     * @param $end
     * @return bool
     */
    public static function isReservationBeforeToday ($start, $end) {
        $today = new \DateTime();
        $testStart = new \DateTime($start);
        $testEnd = new \DateTime($end);
        $today->setTime(0, 0, 0);
        $intStart = $testStart->diff($today);
        $intEnd = $testEnd->diff($today);
        $minutes = $intStart->days * 24 * 60;
        $minutes += $intStart->h * 60;
        $minutes += $intStart->i;
        if (intval($minutes) > 0 && intval($intEnd->invert) == 0) {// +startres <> inputres
            return true;
        }
        return false;
    }

    /**
     * Checks if a reservation crosses another by clan conflict
     *
     * @param $uid
     * @param $start
     * @param $end
     * @return bool
     */
    public static function isReservationCrossed ($uid, $start, $end) {
        $c = array(
            'user_id' => $uid,
            'start_date' => $start,
            'end_date' => $end,
            'start_date2' => $start,
            'end_date2' => $end,
            'start_date3' => $start,
            'end_date3' => $end
        );
        $res = DB::select(DB::raw("select count(id) as count_id from reservations where  user_id = :user_id and" .
            " (reservation_started_at between :start_date and :end_date or" .
            " reservation_ended_at between :start_date2 and :end_date2 or" .
            " reservation_started_at <= :start_date3 and reservation_ended_at >= :end_date3)"), $c);
        return ($res[0]->count_id > 0);
    }

    /**
     * Gets other clan users reservation by User->user_id_ab
     *
     * @return Reservation
     */
    public static function getReservationsOtherClanUser () {
        $cu = new User();
        $reservation = new Reservation();
        $reservation = $reservation->with('guests')->get();
        $reservation->each(function($r) use ($cu) {
            if (isset($r->user_id_ab) && !empty($r->user_id_ab)) {
                $cu = $cu->where('id', '=', $r->user_id_ab)->first();
                $r->user_id_ab_name = $cu->user_login_name;
            } else {
                $r->user_id_ab_name = '';
            }
        });
        return $reservation;
    }

    /**
     * Checks if reservation-request is in its primary period
     * and if auth. user is allowed by primary users reservation
     *
     * @param $resId
     * @param $userIdAb
     * @param $start
     * @return mixed
     */
    public static function isAllowedInSecondaryPeriod ($resId, $userIdAb, $start) {
        $self = new static;

        $startDate = new \DateTime($start);
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $diff = $startDate->diff($today);
        $allowedDate = new \DateTime();
        $allowedDate->modify('+' . $self->setting->setting_num_counter_clan_days . ' day');
        $diffTwo = $allowedDate->diff($today);

        if (isset($resId) && $resId != 'xx') {
                $counterRes = self::where('id', '=', $resId)
                    ->first();
            if(is_object($counterRes)) {
                if($counterRes->user_id_ab != $userIdAb) {
                    return 'not_permitted';
                }
            }
            if(sizeof($counterRes) > 0 && $counterRes->user_id_ab != $userIdAb) {
                return 'not_primary';
            }
        }
        if (intval($diff->days) > intval($diffTwo->days)) {
            return '10';
        }
        return '';
    }

    /**
     * Gets start/end-date of the reservation allowed by primary user
     * @param $userId
     */
    public static function getDataForSecondaryReservationRequest ($userId) {
        $counterResDates = self::where('user_id_ab', '=', $userId)
            ->get();
        Tools::dd($counterResDates);
    }

    /**
     * @param $start
     * @param $end
     * @return int
     * @throws Exception
     */
    public static function nightCounter ($start, $end) {
        $nightCounter = -1;
        $st = new \DateTime(str_replace('_', '-', $start));
        $ed = new \DateTime(str_replace('_', '-', $end));
        $ed->add(new DateInterval('P1D'));
        $intervalOne = new DateInterval('P1D');
        $dateRange = new DatePeriod($st, $intervalOne ,$ed);
        foreach($dateRange as $date) {
            if ($date == null) continue;
            $nightCounter++;
        }
        return $nightCounter;
    }

    public static function setFreeBeds($reservations, $preFix = 'freeBeds_')
    {
        $ar = [];
        $cu = new User();
        $reservations->each(function ($r) use ($ar, $cu, $preFix) {
            if (isset($r->user_id_ab) && !empty($r->user_id_ab)) {
                $cu = $cu->where('id', '=', $r->user_id_ab)->first();
                $r->user_id_ab_name = $cu->user_login_name;
            } else {
                $r->user_id_ab_name = '';
            }
            $r->guests->each(function  ($j) use ($r, $preFix) {
                $role_tax = Role::find($j->role_id);
                $j->role_tax_night = $role_tax->role_tax_night;
                $start = new DateTime(str_replace('_', '-', $j->guest_started_at));
                $end = new DateTime(str_replace('_', '-', $j->guest_ended_at));
                $checkEnd = new DateTime(str_replace('_', '-', $j->guest_ended_at));
                $end->add(new DateInterval('P1D'));
                $interval = new DateInterval('P1D');
                $dateRange = new DatePeriod($start, $interval ,$end);
                foreach($dateRange as $key => $date) {
                    $d = explode('_', $date->format('Y_m_d'));
                    $dd = intval($d[1]) - 1;
                    $d[1] = ($dd < 10) ? '0' . $dd : $dd;
                    if ($date < $checkEnd) {
                        $r->{$preFix . implode('_', $d)} += $j->guest_number;
                    } else {
                        $r->{$preFix . implode('_', $d)} += 0;
                    }
                }
            });
        });
        return $reservations;
    }

    public function isEarlyReservationOnOtherClan(Period $period, User $user, $dates)
    {
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        $today->modify('- ' . $this->setting['setting_num_counter_clan_days'] . ' days');
        $reservationStartDate = new DateTime($dates['resStart'][0]);
        if ($reservationStartDate >= $today) {
            $res = $this->checkExistentReservationByDateV3($dates['resStart'][0], $dates['resEnd'][0]);
        }
    }

    /**
     * @param string $starStr
     * @param string $endStr
     * @param string $call_func
     * @param        $params
     * @throws Exception
     */
    public function loopDates (string $starStr, string $endStr, string $call_func, $params)
    {
        $start = new DateTime($starStr);
        $end = new DateTime($endStr);
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval ,$end);
        foreach ($daterange as $date) {
            call_user_func_array([$this, $call_func], ['date' => $date, 'occupiedBeds' => $params[0], 'beds' => $params[1]]);
        }
    }

    public function checkOccupiedBeds ($params)
    {
        if ($params['occupiedBeds'][$params['date']->format('occupiedBed_' . 'Y_m_d')]) {

        }

    }
}
