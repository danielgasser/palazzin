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
        return $coll;

    }
}
