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
        Period::calculatePeriods();
        return redirect()->back()->with('info_message', 'Perioden berechnet');
    }
}
