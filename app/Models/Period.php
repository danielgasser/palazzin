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
    protected $fillable = ['id', 'period_start', 'period_end', 'clan_id'];


    /**
     * changing months are:
     * Jan, Apr, Jul, Sept, Okt, Nov
     * and always after the last saturday in month
     *
     * @var array
     */
    private static $changingMonths;

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

    /**
     *
     * @param $value
     * @return string
     */
    public function getPeriodEndAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.db-timestamp'));
    }

    protected function getPeriodStart()
    {
        return $this->period_start;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function calculatePeriods(){
        $self = new static;

        self::$changingMonths = array(1, 4, 7, 9, 10);
        $startDate = new \DateTime($self->setting->setting_calendar_start);
        $startDate->setTime(0, 0, 0);
        $endDate = new \DateTime($self->setting->setting_calendar_start);
        $endDate->add(new DateInterval('P' . $self->setting->setting_calendar_duration . 'Y'));
        $endDate->add(new DateInterval('P1Y'));
        $endDate->modify('+1 day');
        $endDate->setTime(0, 0, 0);
        $interval = new DateInterval('P1D');
        $calendarPeriod = new DatePeriod($startDate, $interval, $endDate);
        $lastClan = $self->setting->setting_starting_clan;
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
     * @return mixed
     * @throws Exception
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
     * @param $start
     * @return mixed
     * @throws Exception
     */
    public function getTimelinerPeriods ($start)
    {
        $period = new Period();
        if ($start !== null) {
            $s = new DateTime($start . '-01');
            $end = new DateTime($this->setting->setting_calendar_start);
            $st = $end->modify('+ ' . $this->setting->setting_calendar_duration . ' year')->format('Y-m-d');
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
     * @param $start
     * @return array
     * @throws Exception
     */
    public function getTimelinerDatePickerPeriods ($start)
    {
        $period = new Period();
        $datePickerDates = [];
        $s = new \DateTime($start . '-01');
        $end = new \DateTime($this->setting->setting_calendar_start);
        $st = $end->modify('+ ' . $this->setting->setting_calendar_duration . ' year')->format('Y-m-d');
        $tpID = $this->getNearestPeriod($s, $period);
        $periodsTimeLine =  $period->join('clans', 'clans.id', '=', 'periods.clan_id')
            ->select('periods.id', 'period_start', 'period_end', 'periods.clan_id', 'clans.clan_code', 'clans.clan_description')
            ->whereBetween('period_start', [$tpID->getPeriodStart(), $st])
            ->orderBy('period_start', 'asc')
            ->get();
        $i = 0;
        $periodsTimeLine->each(function($p) use (&$datePickerDates, &$i) {
            $start = new \DateTime($p->period_start);
            $end = new \DateTime($p->period_end);
            $testEnd = new \DateTime($p->period_end);
            // start & end date +1 day because of the last day of period still being bookable for this clan
            if ($i > 0) {
                $start->modify('+ 1 day');
            }
            $end->modify('+ 1 day');
            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($start, $interval ,$end);
            foreach ($daterange as $date) {
                $date->setTime(0, 0, 0, 0);
                if ($date == $testEnd) {
                    $datePickerDates[$date->format('d_m_Y')] = $p->clan_code . '|' . $p->clan_description . '|' . $p->id . '|end';
                } else {
                    $datePickerDates[$date->format('d_m_Y')] = $p->clan_code . '|' . $p->clan_description . '|' . $p->id;
                }
            }
            $i++;
        });
        return $datePickerDates;

    }

    /**
     * @param $s
     * @param $p
     * @return mixed
     */
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
