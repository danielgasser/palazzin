<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveReservation;
use Illuminate\View\View;
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
    public function getCurrentPeriods()
    {
        $user = User::find(Auth::id());
        $start = new \DateTime();
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $periods = new Period();
        $pe = $periods->getTimelinerPeriods($start->format('Y-m'));
        return view('v3.new_reservation')
            ->with('rolesTrans', $rolesTrans)
            ->with('roles', Role::getRolesTaxV3())
            ->with('periods', $pe);
    }

    public function getAllReservationInPeriod()
    {
        $res = new Reservation();
        return $res->getReservationsPerPeriodV3(request()->get('pID'));
    }

    public function getUserReservations()
    {
        $user = User::find(Auth::id());
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $userRes = Reservation::where('user_id', '=', $user->id)
            ->orderBy('reservation_started_at', 'desc')
            ->get();
        $userRes->each(function ($r) {

        });
        return view('v3.all_reservation')
            ->with('roles', Role::getRolesTaxV3())
            ->with('rolesTrans', $rolesTrans)
            ->with('userRes', $userRes);
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

    public function saveReservation(SaveReservation $request)
    {
        $credentials = request()->all();
        $validated = $request->validated();
        $period = new Period(['id' => $credentials['periodID']]);
        $user = User::find(Auth::id());
        $reservation = new Reservation();
        $dates['resStart'] = $reservation->createDbDateFromInput($credentials['reservation_started_at']);
        $dates['resEnd'] = $reservation->createDbDateFromInput($credentials['reservation_ended_at']);
        $dates['guestStart'] = $reservation->createDbDateFromInput($credentials['reservation_guest_started_at']);
        $dates['guestEnd'] = $reservation->createDbDateFromInput($credentials['reservation_guest_ended_at']);
        $reservation->checkEarlyReservationOnOtherClan($period, $user);
        $occupiedBeds = $this->getReservationsPerDateV3();
    }

    public function editReservation($id)
    {
        $user = User::find(Auth::id());
        $start = new \DateTime();
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $periods = new Period();
        $pe = $periods->getTimelinerPeriods($start->format('Y-m'));
        $res = Reservation::find($id);

        if (!is_object($res)) {
            $userRes = Reservation::where('user_id', '=', $user->id)
                ->orderBy('reservation_started_at', 'desc')
                ->get();
            $reservations = Reservation::setFreeBeds($userRes, 'user_Res_occupiedBeds');
            return redirect('all_reservations')
                ->with('userRes', $userRes)
                ->with('reservations', $reservations);
        }
        return view('v3.edit_reservation')
            ->with('userRes', $res)
            ->with('rolesTrans', $rolesTrans)
            ->with('roles', Role::getRolesTaxV3())
            ->with('periods', $pe);
    }

    /**
     *
     * Case 1: start before and end after current reservation
     * Case 2: start before and end before current reservation
     * Case 3: start after and end before current reservation
     * Case 4: start after and end after current reservation
     *
     * @return bool|string
     */
    public function checkExistentReservation()
    {
        $credentials = request()->all();
        $start = $credentials['start'] . ' 00:00:00';
        $end = $credentials['end'] . ' 00:00:00';
        $res = new Reservation();
        $user = User::find(Auth::id());
        $existentRes = $res->checkExistentReservationV3($start, $end, $user->id);
        if (is_object($existentRes)) {
            return $existentRes->id;
        }
        return null;
    }
}
