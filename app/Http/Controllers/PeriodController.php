<?php

namespace App\Http\Controllers;

use Period;
use User;
use Illuminate\Support\Facades\Input;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 05.10.14
 * Time: 16:52
 */

class PeriodController extends Controller
{

    /**
     * triggers the periods calculator, see model
     *
     */
    public function calculatePeriods()
    {
        if (User::isLoggedAdmin()) {
            Period::calculatePeriods();
        }
    }

    /**
     * Periods per month
     *
     * @return mixed Model
     */
    public function getPeriodsPerMonth()
    {
        $period = Period::getPeriods();
        return $period;
    }

    /**
     * Gets all periods
     *
     * @return mixed json
     */
    public function getPeriods()
    {
        $period = new Period();
        return $period->getJSONPeriods();
    }

    /**
     * Gets all periods for the Time-liner
     *
     * @return mixed
     */
    public function getAllPeriodsTimeLine()
    {
        $period = new Period();
        $start = Input::get('start');
        return $period->getTimelinerPeriods($start);
    }

    private function getNearestPeriod($s, $p)
    {
        $tl = $p->select('periods.id', 'periods.period_start')
            ->where('period_start', 'like', $s->format('Y-m') . '%')
            ->first();
        if (is_null($tl)) {
            $s->modify('- 1 month');
            $tl = $this->getNearestPeriod($s, $p);
        }
        return $tl;
    }
    /**
     * Gets the current period by input->id
     *
     * @return mixed
     */
    public function getCurrentPeriod()
    {
        return Period::find(Input::get('period_id'));
    }
}
