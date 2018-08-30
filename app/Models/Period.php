<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 05.02.14
 * Time: 19:48
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Period extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'periods';

    /**
     * The attributes included to mass assignment.
     *
     * @var array
     */
    protected $fillable = array('period_start', 'period_end', 'clan_id');


    /**
     * changing months are:
     * Jan, Apr, Jul, Sept, Okt, Nov
     * and always after the last saturday in month
     *
     * @var array
     */
    private static $changingMonths;

    /**
     *
     * @return mixed
     */
    public function clans(){
        return $this->belongsTo('Clan');
    }

    /**
     *
     * @return mixed
     */
    public function reservations(){
        return $this->hasMany('Reservation', 'reservations');
    }


    /**
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.long-date'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.long-date'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getPeriodStartAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.db-timestamp'));
    }

    public function getPeriodStart()
    {
        return $this->period_start;
    }
    /**
     *
     * @param $value
     * @return string
     */
    public function getPeriodEndAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.db-timestamp'));
    }

    /**
     *
     * @param null $setting
     * @return mixed
     */
    public static function calculatePeriods($setting = null){
        $sets = Setting::getStaticSettings();

        self::$changingMonths = array(1, 4, 7, 9, 10);
        $startDate = new \DateTime($sets->setting_calendar_start);
        $startDate->setTime(0, 0, 0);
        $endDate = new \DateTime($sets->setting_calendar_start);
        $endDate->add(new DateInterval('P' . $sets->setting_calendar_duration . 'Y'));
        $endDate->add(new DateInterval('P1Y'));
        $endDate->modify('+1 day');
        $endDate->setTime(0, 0, 0);
        $interval = new DateInterval('P1D');
        $calendarPeriod = new DatePeriod($startDate, $interval, $endDate);
        $lastClan = $sets->setting_starting_clan;
        DB::statement('SET foreign_key_checks = 0');
        DB::statement('TRUNCATE periods');
        DB::statement('SET foreign_key_checks = 1');
        DB::statement('ALTER TABLE periods AUTO_INCREMENT = 1');
        foreach($calendarPeriod as $calPerDate) {
            if (in_array(intval($calPerDate->format('n')), self::$changingMonths) && intval($calPerDate->format('d')) == 1) {

                $p['period_start'] = (isset($nextPeriod)) ? $nextPeriod : $calPerDate->format('Y-m-d');
                $end = self::getLastWeekdayInMonth($calPerDate, 'Sunday');
                $p['period_end'] = $end->format('Y-m-d');
                $p['clan_id'] = $lastClan;
                $lastClan = self::toggleClan($lastClan);
                Period::firstOrCreate($p);
                $nextPeriod = $end->modify('+1 hour');
            }
        }
        return self::leftJoin('clans', 'clans.id', '=', 'periods.clan_id')
            ->select('period_start', 'period_end', 'clan_id', 'clan_code')
            ->get();
    }

    /**
     * Get auth. users periods
     *
     * @param User $user
     * @return mixed json
     */
    public function getAuthPeriods(User $user){
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        return $periods = self::select('period_start', 'period_end', 'clan_id')
            ->where('clan_id', '=', $user->getUserClan())
            ->where('period_start', '>', $today->format('Y-m-d H:i:s'))
            ->get()
            ->toJSON();
    }

    /**
     * Get all periods from today on
     *
     * @return mixed json
     */
    public static function getJSONPeriods(){
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $arr = array();
        $periods = self::join('clans', 'clans.id', '=', 'periods.clan_id')
            ->select('period_start', 'period_end', 'clan_id', 'clans.clan_code', 'clans.clan_description')
            ->where('period_end', '>=', $today->format('Y-m-d H:i:s'))
            ->get();
        foreach ($periods as $p) {
            $start = new \DateTime($p->period_start);
            $end = new \DateTime($p->period_end);
            while ($start <= $end) {
                $arr[] = $start->format('Y_m_d') . '|' . $p->clan_code;
                $start->modify('+1 day');
                $start->setTime(0, 0, 0);
            }
        }
        return json_encode($arr);
    }

    /**
     * Get period by date or current
     *
     * @return mixed Model
     */
    public static function getCurrentPeriod () {
        $nowts = intval(Session::get('currentCalendarDate'));
        if($nowts == 0) {
            $now = new \DateTime();
            $nowend = new \DateTime();
        } else {
            $now = new \DateTime();
            $now->setTimestamp($nowts);
            $nowend = new \DateTime();
            $nowend->setTimestamp($nowts);
        }
        $now->setTime(0, -1, 0);
        $now->modify('first day of');
        $now->modify('-1 day');
        $now->modify('-1 month');
        $nowend->modify('last day of');
        $nowend->modify('+2 month');
        $nowend->setTime(0, -1, 0);
        $n = $now->format('Y-m-d') . ' 00:00:00';
        $e = $nowend->format('Y-m-d') . ' 00:00:00';
        return Period::whereBetween('period_start', [$n, $e])
            ->select('clan_id', 'period_start', 'period_end')->first();
    }
    /**
     * Get all periods
     *
     * @return mixed json
     */
    public static function getPeriods(){
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $periods = [];
        $sets = Setting::getStaticSettings();

        if (Input::has('this_date')) {
            $staDa = new \DateTime(Input::get('this_date'));
            $neDa = new \DateTime($staDa->format('Y-m-d'));
        } else {
            $staDa = new \DateTime($sets->setting_calendar_start);
            $neDa = new \DateTime($sets->setting_calendar_start);
        }
        $staDa->sub(new DateInterval('P6M'));
        $neDa->add(new DateInterval('P5M'));
        $period = Period::whereBetween('period_start', [$staDa->format('Y-m-d'), $neDa->format('Y-m-d')])
            ->select('periods.id', 'periods.clan_id', 'period_start', 'period_end', 'clans.clan_code', 'clans.clan_description')
            ->join('clans', 'clans.id', '=', 'clan_id')
            ->orderBy('period_start', 'asc')
            ->get();
        $period->each(function ($i)use ($periods) {
            $start = new \DateTime($i->period_start);
            $end = new \DateTime($i->period_end);
            $end->add(new DateInterval('P1D'));
            $interval = new DateInterval('P1M');
            $dateRange = new DatePeriod($start, $interval ,$end);
            $i->periods = array();
            foreach($dateRange as $key => $date) {
                //$d = $date->sub(new DateInterval('P1M'));
                $i->{$date->format('Y_m_d')} = $i->clan_code;
            }
        });
        return $period->toJson();
    }

    /**
     *
     * @param $date
     * @return int
     */
    private function getWeekOfYear($date){
        $y = intval($date->format('Y-m-d H:m:s'));
        $start = new \DateTime($y . '-01-01 00:00:00');
        $startZero = self::setWeekday($start->setDate(intval($start->format('Y')),1, 1), 'Monday', '-1 day');
        $week = intval($startZero->format('%a') + 1);
        while($startZero < $date){
            if(strpos($startZero->format('l'), 'Sunday') !== false){
                $week++;
            }
            $startZero->modify('+1 day');
        }
        return $week;
    }

    public function getTimelinerPeriods ($start)
    {
        $period = new Period();
        if ($start !== null) {
            $setting = Setting::getStaticSettings();
            $s = new \DateTime($start . '-01');
            $e = new \DateTime($setting->setting_calendar_start);
            $st = $e->modify('+ ' . $setting->setting_calendar_duration . ' year')->format('Y-m-d');
            $tpID = $this->getNearestPeriod($s, $period);
            $periodsTimeLine =  $period->join('clans', 'clans.id', '=', 'periods.clan_id')
                ->select('periods.id', 'period_start', 'period_end', 'periods.clan_id', 'clans.clan_code', 'clans.clan_description')
                ->whereBetween('period_start', [$tpID->getPeriodStart(), $st])
                ->orderBy('period_start', 'asc')
                ->get();
        } else {
            $periodsTimeLine =  $period->join('clans', 'clans.id', '=', 'periods.clan_id')
                ->select('periods.id', 'period_start', 'period_end', 'periods.clan_id', 'clans.clan_code', 'clans.clan_description')
                ->orderBy('period_start', 'asc')
                ->get();
        }
        $periodsTimeLine->each(function($p) {
            $start = new \DateTime($p->period_start);
            $num = cal_days_in_month(CAL_GREGORIAN, $start->format('m'), $start->format('Y'));
            if ($start->format('d') != 1) {
                $start->setDate($start->format('Y'), $start->format('n'), $num);
                $start->modify('+1 day');

            }
            $p->period_start_new = $start->format('Y-m-d H:m:s');
        });
        return $periodsTimeLine;

    }


    /**
     * Sets the day of the week from a given $date
     * $weekday the desired weekday to start with
     *
     * @param $date
     * @param $weekday
     * @param $step
     * @return mixed date
     */
    private function setWeekday($date, $weekday, $step){
        $d = $date;
        while(strpos($d->format('l'), $weekday) === false){
            $d->modify($step);
        }
        return $d;
    }

    private function getNearestPeriod($s, $p)
    {
        $tl = $p->select('periods.id', 'periods.period_start')
            ->where('period_start', '<', $s->format('Y-m-d'))
            ->where('period_end', '>', $s->format('Y-m-d'))
            ->first();
        if (is_null($tl)) {
            $s->modify('- 1 month');
            $tl = $this->getNearestPeriod($s, $p);
        }
        return $tl;
    }

    /**
     *
     * @param $date
     * @param $weekday
     * @return int
     */
    private function setStartingDay($date, $weekday){
        $days = 0;
        for($i = intval($date->format('d')); $i > 0; $i--){
            $date->setDate(intval($date->format('Y')), intval($date->format('n')), $i);
            if ($date->format('l') == $weekday){
               $days++;
            }
        }
        return $days;
    }

    /**
     *
     * @param $date
     * @param $weekday
     * @return mixed
     */
    private static function getLastWeekdayInMonth($date, $weekday){
        $d = $date;
        $e = cal_days_in_month(CAL_GREGORIAN, intval($d->format('m')), intval($d->format('Y')));

        for($i = $e; $i > 0; $i--){
            $d->setDate(intval($d->format('Y')), intval($d->format('n')), $i);
             if ($d->format('l') == $weekday){
                 return $d;
             }
        }
        return $date;
    }

    /**
     *
     * @param $a
     * @return int
     */
    private static function toggleClan($a){
        return ($a == 1) ? 2 : 1;
    }
}
