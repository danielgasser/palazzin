<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveReservation;
use Illuminate\Http\Request;
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
        $userClanID = $user->getUserClan();
        $start = new \DateTime();
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $periods = new Period();
        $pe = $periods->getTimelinerPeriods($start->format('Y-m'));
        $peDP = $periods->getTimelinerDatePickerPeriods($start->format('Y-m'));
        $reservationsPerPeriod = $this->getReservationsPerDateV3($pe->first()->id);
        $guestBlade = view('logged.dialog.guest', ['rolesTrans' => $rolesTrans, 'i' => 0]);
        $guestEntryView = $guestBlade->render();
        $guestEntryView = strtr($guestEntryView,"\n\r","  ");
        return view('v3.new_reservation')
            ->with('rolesTrans', $rolesTrans)
            ->with('roleTaxes', Role::getRolesTaxV3())
            ->with('guestEntryView', $guestEntryView)
            ->with('reservationsPerPeriod', $reservationsPerPeriod)
            ->with('periods', $pe)
            ->with('userClan', $user->getUserClanName($userClanID))
            ->with('periodsDatePicker', $peDP);
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
     * @param null $periodID
     * @return mixed
     */
    public function getReservationsPerDateV3($periodID = null, $isJson = true)
    {
        $reservation = new Reservation();
        if (is_null($periodID)) {
            $periodID = Input::get('periodID');
        }
        return $reservation->getReservationsPerPeriodV3($periodID, $isJson);
    }

    public function saveReservation(SaveReservation $request)
    {
        $credentials = request()->all();
        $validated = $request->validated();
       // ToDo if beds == 1 only check total beds from localStorage
        $resStart = Reservation::createDbDateFromInput($validated['reservation_started_at']);
        $resEnd = Reservation::createDbDateFromInput($validated['reservation_ended_at']);
        $start = new \DateTime($resStart[0]);
        $end = new \DateTime($resEnd[0]);
        $args['reservation_nights'] = $start->diff($end)->format('%a');
        $args['reservation_reminder_sent'] = 0;
        $args['reservation_bill_sent'] = 0;
        $args['reservation_reminder_sent_at'] = '0000-00-00 00:00:00';
        $args['user_id'] = Auth::id();
        $args['period_id'] = intval($credentials['periodID']);
        $args['reservation_started_at'] = $start->format('Y-m-d H:i:s');
        $args['reservation_ended_at'] = $end->format('Y-m-d H:i:s');
        if (!isset($validated['user_id_ab'])) {
            $args['user_id_ab'] = 0;
        } else {
            $args['user_id_ab'] = $credentials['user_id_ab'];
        }
        $res = new Reservation();
        $user = User::find(Auth::id());
        $res->users()->associate($user);
        $res->fill($args);
        $saved = $res->save();
        if (array_key_exists('reservation_guest_started_at', $validated)) {
            for($i = 0; $i < sizeof($validated['reservation_guest_started_at']); $i++) {
                $guest = new \Guest();
                $role = Role::find($validated['reservation_guest_guests'][$i]);
                $guest->roles()->associate($role);
                $args = [
                    'reservation_id' => $res->id,
                    'guest_started_at' => $validated['reservation_guest_started_at'][$i],
                    'guest_ended_at' => $validated['reservation_guest_ended_at'][$i],
                    'guest_number' => $validated['reservation_guest_num'][$i],
                    'guest_night' => $validated['number_nights'][$i],
                    'role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax_role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax' => $credentials['price'][$i],
                    'guest_title' => $credentials['price'][$i],
                ];
                $guest->fill($args);
                $res->guests()->save($guest);
            }
        }
        if ($saved) {
            return back()
                ->with('info_message', trans('errors.data-saved', ['a' => 'Die', 'data' => 'Reservation']));
        }
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
        $existentRes = $res->checkExistentReservationByUidV3($start, $end, $user->id);
        if (is_object($existentRes)) {
            return $existentRes->id;
        }
        return null;
    }
}
