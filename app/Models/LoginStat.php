<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 05.02.14
 * Time: 19:48
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoginStat extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'login_stats';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'user_id'
    );

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->belongsTo('User', 'users');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.short-date-time'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.db-date'));
    }

    /**
     * @param null $inputs
     * @return stdClass
     * @throws Exception
     */
    public function getLoginsByDate($inputs = null){
        if ($inputs == null) {
            $today = new \DateTime();
            $tomorrow = new \DateTime();
            $today->modify('-1 month');
            $tomorrow->modify('+1 month');
            $startMonth = intval($today->format('m'));
            $startYear = intval($today->format('Y'));
            $endMonth = intval($tomorrow->format('m'));
            $endYear = intval($tomorrow->format('Y'));
        } else {
            $startMonth = intval($inputs['searchParams'][0]) - 1;
            $startYear = intval($inputs['searchParams'][1]);
            $endMonth = intval($inputs['searchParams'][2]);
            $endYear = intval($inputs['searchParams'][3]);
        }

        $start = $startYear . '-' . $this->smallerThenTen($startMonth) . '-01';
        $end = $endYear . '-' . $this->smallerThenTen($endMonth) . '-' . $this->getLastDayInMonth($endMonth, $endYear);

        $coll = self::whereBetween('login_stats.created_at', [$start, $end])
            ->join('users', 'users.id', '=', 'login_stats.user_id')
            ->groupBy('users.id')
            ->get();
        $coll->each(function($c){
            $c->userCount = $c->where('login_stats.user_id', '=', $c->id)->count();
            $c->userDates = $c->where('login_stats.user_id', '=', $c->id)->get()->toArray();
        });
        if($coll->count() == 0){
            $ls = new stdClass();
            $ls->startTextDate = \Carbon\Carbon::createFromFormat('m-Y', $inputs['searchParams'][0] . '-' . $inputs['searchParams'][1])->formatLocalized(trans('formats.long-month-short-year'));
            $ls->endTextDate = \Carbon\Carbon::createFromFormat('m-Y', $inputs['searchParams'][2] . '-' . $inputs['searchParams'][3])->formatLocalized(trans('formats.long-month-short-year'));
            $ls->startDate = \Carbon\Carbon::createFromFormat('m-Y', $inputs['searchParams'][0] . '-' . $inputs['searchParams'][1])->formatLocalized(trans('formats.calc-month-short-year'));
            $ls->endDate = \Carbon\Carbon::createFromFormat('m-Y', $inputs['searchParams'][2] . '-' . $inputs['searchParams'][3])->formatLocalized(trans('formats.calc-month-short-year'));
            $ls->userCount = 0;
            $ls->monthBigger = ($inputs['searchParams'][0] > $inputs['searchParams'][2]);
            $ls->yearBigger = ($inputs['searchParams'][1] > $inputs['searchParams'][3]);
            $ls->error = trans('validation.no_data.login_stats', array('start' =>$ls->startTextDate, 'end' => $ls->endTextDate));
            return $ls;
        }
        $coll[0]['startDate'] = \Carbon\Carbon::createFromFormat('Y-m-d', $start)->formatLocalized(trans('formats.calc-month-short-year'));
        $coll[0]['endDate'] = \Carbon\Carbon::createFromFormat('Y-m', $endYear . '-' . $endMonth)->formatLocalized(trans('formats.calc-month-short-year'));
        $coll[0]['startTextDate'] = utf8_encode(\Carbon\Carbon::createFromFormat('Y-m-d', $start)->formatLocalized(trans('formats.long-month-short-year')));
        $coll[0]['endTextDate'] = utf8_encode(\Carbon\Carbon::createFromFormat('Y-m-d', $end)->formatLocalized(trans('formats.long-month-short-year')));
        return $coll;
    }

    /**
     * Filter logins by month/year
     *
     * @param $credentials
     * @return $this
     */
    public function filterLogins ($credentials) {
        $startMonth= $credentials[0];
        $endMonth = $credentials[2];
        $startYear = $credentials[1];
        $endYear = $credentials[3];
        if($startMonth > $endMonth || $startYear > $endYear){
            $endMonth = $startMonth;
            $endYear = $startYear;
        }
        $this->startTextDate = \Carbon\Carbon::createFromFormat('m-Y', $startMonth . '-' . $startYear)->formatLocalized(trans('formats.long-month-short-year'));
        $this->endTextDate = \Carbon\Carbon::createFromFormat('m-Y', $endMonth . '-' . $endYear)->formatLocalized(trans('formats.long-month-short-year'));
        $this->startDate = \Carbon\Carbon::createFromFormat('m-Y', $startMonth . '-' . $startYear)->formatLocalized(trans('formats.calc-month-short-year'));
        $this->endDate = \Carbon\Carbon::createFromFormat('m-Y', $endMonth . '-' . $endYear)->formatLocalized(trans('formats.calc-month-short-year'));
        $this->userCount = 0;
        $this->monthBigger = ($startMonth > $endMonth);
        $this->yearBigger = ($startYear > $endYear);
        $this->error = trans('validation.no_data.login_stats', array('start' =>$this->startTextDate, 'end' => $this->endTextDate));
        return $this;
    }

    /**
     * Filter logins by month/year
     *
     * @param $year
     * @return $this
     */
    public function filterLoginsStats ($year = array('2015-%')) {
        $coll = $this->select('login_stats.created_at', DB::raw('count(login_stats.created_at) as data'), 'users.user_first_name', 'users.user_name', 'users.id', 'login_stats.user_id')
            ->leftJoin('users', 'users.id', '=', 'login_stats.user_id')
            ->where('login_stats.created_at', 'like', $year[0])
            ->orWhere(function ($query) use($year) {
                foreach($year as $y){
                    $query->orWhere('login_stats.created_at', 'like', $y);
                }
            })
            ->groupBy('login_stats.user_id')
            ->get();
        /*
        $coll->each(function($c){
            $c->data = $c->where('login_stats.user_id', '=', $c->id)->count();
            $c->userDates = $c->where('login_stats.user_id', '=', $c->id)->get()->toArray();
            $c->name = $c->user_first_name . ' ' . $c->user_name;
        });
        */
        return $coll;

    }

    /**
     * Gets all logins
     *
     * @return mixed Collection
     */
    public static function getLogins(){
        $c = new \LoginStat();
        $coll = $c->select('login_stats.created_at', 'login_stats.updated_at', 'users.user_first_name', 'users.user_name', 'users.id', 'login_stats.user_id')
            ->leftJoin('users', 'users.id', '=', 'login_stats.user_id')
            ->groupBy('users.user_login_name')
            ->get();
        $coll->each(function($c){
            $c->userCount = $c->where('login_stats.user_id', '=', $c->id)->count();
            $c->userDates = $c->where('login_stats.user_id', '=', $c->id)->get()->toArray();
        });
        return $coll;
    }

    /**
     * Returns a string with preceding '0' from $i < 10
     *
     * @param $i
     * @return string
     */
    protected function smallerThenTen($i){
        return (intval($i) < 10) ? '0' . $i : $i;
    }
    /**
     * @param $m
     * @param $y
     * @return int
     */
    protected function getLastDayInMonth($m, $y){
        return cal_days_in_month(CAL_GREGORIAN, intval($m), intval($y));
    }


}
