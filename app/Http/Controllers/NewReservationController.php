<?php

namespace App\Http\Controllers;

use Period;
use Reservation;
use Role;
use User;
use Illuminate\Support\Facades\Input;
use Auth;

/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 08.05.2018
 * Time: 12:12
 */

class NewReservationController extends Controller
{
    public function getNewReservations()
    {
        $user = User::find(Auth::id());
        $start = new \DateTime();
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $periods = new Period();
        $reservation = new Reservation();
        $p = $periods->getTimelinerPeriods($start->format('Y-m'));
        $r = $reservation->getReservationsPerPeriodV3(18);
        return view('v3.reservation')
            ->with('rolesTrans', $rolesTrans)
            ->with('roles', Role::getRolesTaxV3())
            ->with('periods', $p)
            ->with('reservations', $r)
            ->with('clan_name', $user->getUserClanName($user->clan_id));
    }

    public function getAllReservationInPeriod($pID)
    {
        dd($pID);
    }


    /**
     * Gets reservations by period date
     *
     * @return mixed Model
     */
    public function getReservationsPerDateV3()
    {
        $reservation = new Reservation();
        $periodID = Input::get('periodID');
        return $reservation->getReservationsPerPeriodV3($periodID);
    }
}
