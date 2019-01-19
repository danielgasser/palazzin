<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveReservation;
use function foo\func;
use Period;
use Reservation;
use Role;
use Guest;
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

    public function newReservation()
    {
        $args = $this->getReservationInfos();
        return view('v3.new_reservation')
            ->with('rolesTrans', $args['rolesTrans'])
            ->with('roleTaxes', $args['roleTaxes'])
            ->with('guestEntryView', $args['guestEntryView'])
            ->with('reservationsPerPeriod', $args['reservationsPerPeriod'])
            ->with('periods', $args['periods'])
            ->with('userClan', $args['userClan'])
            ->with('periodsDatePicker', $args['periodsDatePicker']);

    }

    public function editReservation($res_id)
    {
        $args = $this->getReservationInfos();
        $user = User::find(Auth::id());
        $res = Reservation::where('id', '=', $res_id)->with('guests')->first();

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
            ->with('rolesTrans', $args['rolesTrans'])
            ->with('roleTaxes', $args['roleTaxes'])
            ->with('guestEntryView', $args['guestEntryView'])
            ->with('reservationsPerPeriod', $args['reservationsPerPeriod'])
            ->with('periods', $args['periods'])
            ->with('userClan', $args['userClan'])
            ->with('periodsDatePicker', $args['periodsDatePicker']);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    protected function getReservationInfos()
    {
        $args = [];
        $user = User::find(Auth::id());
        $userClanID = $user->getUserClan();
        $start = new \DateTime();
        $periods = new Period();
        $checkPeriod = Period::getCurrentPeriod();


        $args['userClan'] = $user->getUserClanName($userClanID);
        $args['rolesTrans'] = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $guestBlade = view('logged.dialog.guest_entry', ['rolesTrans' => $args['rolesTrans'], 'i' => 0]);
        $guestEntryView = $guestBlade->render();
        $args['guestEntryView'] = strtr($guestEntryView,"\n\r","  ");

        $args['roleTaxes'] = Role::getRolesTaxV3();
        $args['periods'] = $periods->getTimelinerPeriods($start->format('Y-m'));
        $args['periodsDatePicker'] = $periods->getTimelinerDatePickerPeriods($start->format('Y-m'));
        $args['reservationsPerPeriod'] = $this->getReservationsPerDateV3($args['periods']->first()->id);
        return $args;
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
            ->with('guests')
            ->get();
        $userRes->each(function ($ur) {
            if ($ur->guests->isEmpty()) {
                $ur->guests = [];
            }
        });
        return view('v3.all_reservation')
            ->with('roles', Role::getRolesTaxV3())
            ->with('rolesTrans', $rolesTrans)
            ->with('userRes', $userRes->toJson());
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
        $resID = request()->all('id');
        $resStart = Reservation::createDbDateFromInput($validated['reservation_started_at']);
        $resEnd = Reservation::createDbDateFromInput($validated['reservation_ended_at']);
        $start = new \DateTime($resStart[0]);
        $end = new \DateTime($resEnd[0]);
        $user = User::find(Auth::id());
        $args['reservation_nights'] = ($start->diff($end)->format('%a') - 1 === 0) ? 1 : $start->diff($end)->format('%a') - 1;
        $args['reservation_reminder_sent'] = 0;
        $args['reservation_bill_sent'] = 0;
        $args['reservation_reminder_sent_at'] = '0000-00-00 00:00:00';
        $args['user_id'] = Auth::id();
        $args['period_id'] = intval($credentials['periodID']);
        $args['reservation_title'] = $credentials['reservation_title'];
        $args['reservation_started_at'] = $start->format('Y-m-d H:i:s');
        $args['reservation_ended_at'] = $end->format('Y-m-d H:i:s');
        if (!isset($validated['user_id_ab'])) {
            $args['user_id_ab'] = 0;
        } else {
            $args['user_id_ab'] = $credentials['user_id_ab'];
        }
        if (!is_null($resID['id'])) {
            $res = Reservation::find(request()->all('id'));
            $res->users()->associate($user);
        } else {
            $res = new Reservation();
        }
        $res->fill($args);
        $saved = $res->save();
        if (array_key_exists('reservation_guest_started_at', $validated)) {
            for($i = 0; $i < sizeof($validated['reservation_guest_started_at']); $i++) {
                $guest = new \Guest();
                $role = Role::find($validated['reservation_guest_guests'][$i]);
                $guest->roles()->associate($role);
                $args = [
                    'reservation_id' => $res->id,
                    'guest_started_at' => Reservation::createDbDateFromInput($validated['reservation_guest_started_at'][$i])[0],
                    'guest_ended_at' => Reservation::createDbDateFromInput($validated['reservation_guest_ended_at'][$i])[0],
                    'guest_number' => $validated['reservation_guest_num'][$i],
                    'guest_night' => $validated['number_nights'][$i],
                    'role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax_role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax' => $credentials['hidden_reservation_guest_price'][$i],
                    'guest_title' => $credentials['hidden_guest_title'][$i],
                ];
                $guest->fill($args);
                $res->guests()->save($guest);
            }
        }
        if ($saved) {
            return redirect('all_reservations')
                ->with('info_message', trans('errors.data-saved', ['a' => 'Die', 'data' => 'Reservation']));
        }
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

    public function deleteReservation()
    {
        $resID = request()->all('res_id');
        $res = Reservation::find($resID['res_id']);
        $today = new \DateTime();
        $start = new \DateTime($res->reservation_ended_at);
        if ($today > $start) {
            return json_encode(['error' => 'no_delete_reservation']);
        }
        // ToDo check if reservation is before today => don't delete but message
        $res->guests()->delete();
        $res->delete();
        return json_encode(['success' => 'deleted_reservation']);
    }
}
