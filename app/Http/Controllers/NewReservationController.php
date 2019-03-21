<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveReservation;
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
        $user = User::find(Auth::id());
        $userRes = Reservation::where('user_id', '=', $user->id)
            ->orderBy('reservation_started_at', 'desc')
            ->get();
        $clans = \Clan::pluck('clan_description', 'clan_code')->toArray();
        $reservations = Reservation::setFreeBeds($userRes, 'user_Res_Dates_', true, 'Y_m_d', false);
        return view('v3.new_reservation')
            ->with('rolesTrans', $args['rolesTrans'])
            ->with('roleTaxes', $args['roleTaxes'])
            ->with('guestEntryView', $args['guestEntryView'])
            ->with('reservationsPerPeriod', $args['reservationsPerPeriod'])
            ->with('allClans', $clans)
            ->with('periods', $args['periods'])
            ->with('userRes', $reservations)
            ->with('userClan', $args['userClan'])
            ->with('periodsDatePicker', $args['periodsDatePicker']);

    }

    public function editReservation($res_id)
    {
        $args = $this->getReservationInfos();
        $user = User::find(Auth::id());
        $res = Reservation::where('id', '=', $res_id)
            ->with('guests')
            ->get();

        if (!is_object($res)) {
            $userRes = Reservation::where('user_id', '=', $user->id)
                ->orderBy('reservation_started_at', 'desc')
                ->get();
            $reservations = Reservation::setFreeBeds($userRes, 'user_Res_occupiedBeds');
            return redirect('all_reservations')
                ->with('userRes', $userRes)
                ->with('reservations', $reservations);
        }
        $today = new \DateTime();
        $today->modify('+1 day');
        $today->setTime(23, 59, 59, 999);
        $res->each(function ($ur) use($today) {
            $resEnd = new \DateTime($ur->reservation_ended_at);
            $ur->sum_total = 0.00;
            $ur->sum_guest = 1;
            if ($ur->guests->isEmpty()) {
                $ur->guests = new Guest([]);
            } else {
                $ur->guests->each(function ($g) use ($ur, $today) {
                    $ur->sum_total += $g->guest_tax * $g->guest_number * $g->guest_night;
                    $ur->sum_guest += $g->guest_number;
                    $g->guest_tax = number_format($g->guest_tax, 2, '.', "'");
                    $g->guest_total = number_format($g->guest_tax * $g->guest_number, 2, '.', "'");
                    $g->guest_all_total = number_format($g->guest_tax * $g->guest_number * $g->guest_night, 2, '.', "'");
                });
            }
            $ur->sum_total =  number_format($ur->sum_total, 2, '.', "'");
            $ur->sum_total_hidden =  $ur->sum_total;
            $ur->editable = ($today <= $resEnd->modify('+ 1 day'));
        });
        $my_reservations = Reservation::setFreeBeds($res, 'user_Res_Dates_', false, 'Y_m_d', false);

        return view('v3.edit_reservation')
            ->with('userRes', $res)
            ->with('rolesTrans', $args['rolesTrans'])
            ->with('roleTaxes', $args['roleTaxes'])
            ->with('guestEntryView', $args['guestEntryView'])
            ->with('reservationsPerPeriod', $args['reservationsPerPeriod'])
            ->with('periods', $args['periods'])
            ->with('userClan', $args['userClan'])
            ->with('my_reservations', json_encode($my_reservations, JSON_HEX_APOS))
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
        $today = new \DateTime();
        $today->modify('+1 day');
        $today->setTime(0, 0, 0, 0);
        $userRes->each(function ($ur) use($today) {
            $resEnd = new \DateTime($ur->reservation_ended_at);
            $ur->sum_total = 0.00;
            $ur->sum_guest = 0;
            if ($ur->guests->isEmpty()) {
                $ur->guests = [];
            } else {
                $ur->guests->each(function ($g) use ($ur, $today) {
                    $ur->sum_total += $g->guest_tax * $g->guest_number * $g->guest_night;
                    $ur->sum_guest += $g->guest_number;
                    $g->guest_tax = number_format($g->guest_tax, 2, '.', "'");
                    $g->guest_total = number_format($g->guest_tax * $g->guest_number, 2, '.', "'");
                    $g->guest_all_total = number_format($g->guest_tax * $g->guest_number * $g->guest_night, 2, '.', "'");
                });
            }
            $ur->sum_total =  number_format($ur->sum_total, 2, '.', "'");
            $ur->editable = ($today <= $resEnd->modify('+ 1 day'));
        });
        return view('v3.all_reservation')
            ->with('roles', Role::getRolesTaxV3())
            ->with('rolesTrans', $rolesTrans)
            ->with('userRes', $userRes);
    }

    public function AdminGetAllReservations()
    {
        $user = User::find(Auth::id());
        $res = new Reservation();
        $checkPeriod = Period::getCurrentPeriod();
        $rolesTrans = Role::getRolesForGuestV3((intval($user->clan_id) == intval($checkPeriod->clan_id)));
        $allRes = $res->getReservations();
        $allRes->each(function ($ur) {
            if ($ur->guests->isEmpty()) {
                $ur->guests = [];
            }
        });
        return view('logged.admin.admin_all_reservation')
            ->with('roles', Role::getRolesTaxV3())
            ->with('rolesTrans', $rolesTrans)
            ->with('allRes', $allRes);
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
        $resID = request()->all('id');
        $resStart = Reservation::createDbDateFromInput($validated['reservation_started_at']);
        $resEnd = Reservation::createDbDateFromInput($validated['reservation_ended_at']);
        $start = new \DateTime($resStart[0]);
        $end = new \DateTime($resEnd[0]);
        $user = User::find(Auth::id());
        $args['reservation_nights'] = ($start->diff($end)->format('%a') - 1 === 0) ? 1 : $start->diff($end)->format('%a');
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
            $res = Reservation::find($resID)->first();
            $res->users()->associate($user);
        } else {
            $res = new Reservation();
        }
        $res->fill($args);
        $saved = $res->save();
        if (array_key_exists('reservation_guest_started_at', $validated)) {
            for($i = 0; $i < sizeof($validated['reservation_guest_started_at']); $i++) {
                $args = [
                    'reservation_id' => $res->id,
                    'guest_started_at' => Reservation::createDbDateFromInput($validated['reservation_guest_started_at'][$i])[0],
                    'guest_ended_at' => Reservation::createDbDateFromInput($validated['reservation_guest_ended_at'][$i])[0],
                    'guest_number' => $validated['reservation_guest_num'][$i],
                    'guest_night' => $validated['number_nights'][$i],
                    'role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax_role_id' => $validated['reservation_guest_guests'][$i],
                    'guest_tax' => $credentials['price'][$i],
                    'guest_title' => $credentials['hidden_guest_title'][$i],
                ];
                if (!isset($credentials['guest_id'][$i])) {
                    $guest = new Guest();
                } else {
                    $guest = Guest::find($credentials['guest_id'][$i]);
                }
               // $guest->roles()->associate($role);
                $guest->fill($args);
                if (!isset($credentials['guest_id'][$i])) {
                   // $guest->push();
                } else {
                }
                $res->guests()->save($guest);
                //
            }
            $saved = $res->push();
        }
        if ($saved) {
            $resInfo = $this->getReservationInfos();
            $my_reservations = Reservation::setFreeBeds($res, 'user_Res_Dates_', false, 'Y_m_d', false);
            return redirect('edit_reservation/' . $res->id)
                ->with('userRes', $res)
                ->with('rolesTrans', $resInfo['rolesTrans'])
                ->with('roleTaxes', $resInfo['roleTaxes'])
                ->with('guestEntryView', $resInfo['guestEntryView'])
                ->with('reservationsPerPeriod', $resInfo['reservationsPerPeriod'])
                ->with('periods', $resInfo['periods'])
                ->with('userClan', $resInfo['userClan'])
                ->with('my_reservations', json_encode($my_reservations, JSON_HEX_APOS))
                ->with('periodsDatePicker', $resInfo['periodsDatePicker'])
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
        $res->guests()->delete();
        $res->delete();

        return json_encode(['success' => 'deleted_reservation']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function AdminSearchAllReservations()
    {
        $reservation = new Reservation();
        if (sizeof(Input::all()) == 0) {
            return view('logged.keeper.reservation')
                ->with('allReservations', $reservation->getReservations());
        }
        return Response::json($reservation->getReservationsAjax(Input::except('resetKeeper')));
    }

}
