<?php

namespace App\Http\Controllers;

use Bill;
use Clan;
use Guest;
use LocalStorage;
use Period;
use Reservation;
use Role;
use Setting;
use User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Response;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 05.10.14
 * Time: 06:55
 */

class ReservationController extends Controller
{

    /**
     * Gets all Reservations, Language strings, Roles, Periods for the calendar
     *
     * @return mixed View
     */
    public function getReservations()
    {
        $reservations = Reservation::getReservationsOtherClanUser();
        $userRes = new Reservation();
        $userReservations = $userRes->getAuthReservationsArrayJS();
        $user = User::find(Auth::id());
        if (is_null($user)) {
            return redirect('/');
        }
        $checkPeriod = Period::getCurrentPeriod();
        $checkRolesForGuest = (intval($user->clan_id) == intval($checkPeriod->clan_id));
        foreach (User::find(Auth::id())->getRoles() as $u) {
            $userRole[] = $u->role_code;
        }
        $userClan = $user->getUserClan();
        $userClanName = $user->getUserClanName($user->clan_id);
        $userList = $user->getSimpleUsersListByNotThisClan($user->clan_id);
        $userID = $user->id;
        $set = Setting::getStaticSettings();
        $userPeriods = Period::getPeriods();
        $allPeriods = Period::select('period_start', 'period_end', 'clan_id')->get();
        $rolesTrans = Role::getRolesForGuest($checkRolesForGuest);
        $rolesPureDropDown = Role::getRolesDropDown();
        $langStrings = [
            'calweek_short' => trans('calendar.calweek-short'),
            'calweek' => trans('calendar.calweek'),
            'gototoday' => trans('calendar.gototoday'),
            'weekday' =>  trans('calendar.calweek'),
            'weekdays' => trans('calendar.weekdays'),
            'weekdays_short' => trans('calendar.weekdays-short'),
            'reset' => trans('dialog.reset'),
            'dialog' => trans('dialog')

        ];
        return view('logged.reservation')
            ->with('settings', $set)
            ->with('allClans', Clan::select('id', 'clan_description', 'clan_code')->get())
            ->with('clan', $userClan)
            ->with('clan_name', $userClanName)
            ->with('allReservations', $reservations)
            ->with('userRes', $userReservations)
            ->with('reservation', null)
            ->with('periods', $userPeriods)
            ->with('periodsAll', $allPeriods)
            ->with('roles', $rolesPureDropDown)
            ->with('userRoles', $userRole)
            ->with('rolesTrans', $rolesTrans)
            ->with('userId', $userID)
            ->with('userlist', $userList)
            ->with('langStrings', $langStrings);
    }

    /**
     * List reservation view
     *
     * @return mixed View
     */
    public function showAllReservations()
    {
        $reservation = new Reservation();
        $users = User::select('users.id', 'user_first_name', 'user_name')
            ->orderBy('user_name', 'asc')
            ->get();
        return view('logged.admin.reservation')
            ->with('allReservations', $reservation->getReservations())
            ->with('allBills', $reservation->getCountReservations())
            ->with('users', $users);
    }


    /**
     * Gets reservations by date
     *
     * @return mixed Model
     */
    public function getReservationsPerDate()
    {
        $reservation = new Reservation();
        return $reservation->getReservationsPerDateById();
    }

    /**
     * // ToDo UNUSED METHOD!!!
     *
     * @return mixed TEST
     */
    public function getReservationsPerUser()
    {
        $reservations = new Reservation();
        $reservations = $reservations->where('user_id', '=', Auth::user()->id)
            ->with([
                'guests' => function ($q) {
                    $q->with('roles');
                },
            ])
            ->get();
        return view('user.reservation')
            ->with('allReservations', $reservations);
    }

    /**
     * Adds a new or saves a reservation with guests
     *
     * @return mixed Redirect empty
     */
    public function saveReservation()
    {
        Input::flash();
        $set = Setting::getStaticSettings();

       // $this->putToSession();
        $resID = Input::get('res_id');
        $userId = Auth::user()->id;
        $userIdAb = Input::get('userIdAb');
        if (empty($userIdAb) || $userIdAb == 'xx') {
            $userIdAb = 0;
        }
        $user = User::find($userId);
        $start = explode('.', Input::get('show_reservation_started_at'));
        $end = explode('.', Input::get('show_reservation_ended_at'));
        $startDate = $start[2] . '-' . $start[1] . '-' . $start[0] . ' 00:00:00';
        $endDate = $end[2] . '-' . $end[1] . '-' . $end[0] . ' 00:00:00';
        $currentPeriod = Period::find(Input::get('period_id'));
        $periodStartDate = new \DateTime($currentPeriod->period_start);
        $periodEndDate = new \DateTime($currentPeriod->period_end);
        $resCheck = false;
        $tooEarly = '';
       // $periodStartDate->modify('-1 day');
        $periodEndDate->modify('+1 day');
        $periodStartDate->setTime(0, 0);
        $periodEndDate->setTime(0, 0);
        if ($user->user_new == 1) {
            Session::put('error', trans('reservation.warnings.no_profile'));
            return Redirect::back()
                ->withErrors([trans('reservation.warnings.no_profile')]);
        }
        $nightCounter = Reservation::nightCounter($startDate, $endDate);
        if ($nightCounter < 1 && !in_array(json_decode(Session::get('otherClanRoleId'))->id, Input::get('reservation_guest_guests'))) {
            Session::put('error', trans('reservation.warnings.guest_zero'));
            return Redirect::back()
                ->withErrors([trans('reservation.warnings.guest_zero')]);
        }
        $ed = new \DateTime(str_replace('_', '-', $endDate));
        $diffCheck = $ed->diff($periodStartDate);
        if (($user->clan_id != $currentPeriod->clan_id) && $set->setting_counter_clan_on_off == 1 && $diffCheck->d > 1) {
            $counterRes = Reservation::isAllowedInSecondaryPeriod(Input::get('existentResId'), $userIdAb, $startDate);
            if (strlen($counterRes) > 0) {
                $today = new \DateTime();
                $today->setTime(0, 0, 0);
                if ($counterRes == 'not_primary' || $counterRes == 'not_permitted') {
                    // has to contact primary user
                    $tooEarly = trans('reservation.warnings.not_primary');
                }
                if ($counterRes == '10') {
                    $tooEarly = trans('reservation.warnings.not_primary_before', ['days' => $set->setting_num_counter_clan_days]);
                }
                Session::put('res_error', $tooEarly);
                return Redirect::back()
                    ->withErrors([$tooEarly]);
            }
        }
        if (!isset($resID) || $resID == '') {
            $resCheck = Reservation::isReservationCrossed($userId, $startDate, $endDate);
        }
        if ($resCheck) {
            Session::put('res_error', trans('reservation.warnings.cross_reserv'));
            return Redirect::back()
               ->withErrors([trans('reservation.warnings.cross_reserv')]);
        }
        $beforeToday = Reservation::isReservationBeforeToday($startDate, $endDate);
        if ($beforeToday) {
            Session::put('res_error', trans('reservation.warnings.before_today'));
            return Redirect::back()
                ->withErrors([trans('reservation.warnings.before_today')]);
        }
        $credentials = [
            'reservation_started_at' => $startDate,
            'reservation_ended_at' => $endDate,
            'user_id' => $userId,
            'user_id_ab' => $userIdAb,
            'period_id' => Input::get('period_id'),
            'existentResId' => Input::get('existentResId'),
            'reservation_nights' => $nightCounter,
        ];
        // first day of new period allowed, because of Abreise am 1. Tag der neuen Periode
        if (Input::get('existentResId') != null) {
            $rules = [
                'user_id' => 'required',
                'period_id' => 'required',
                'reservation_nights' => 'required|min:1',
            ];
        } elseif ($periodStartDate->format('Y-m-d') == Input::get('show_reservation_ended_at')) {
            $rules = [
                'reservation_ended_at' => 'required|before:' . $periodEndDate->format('Y-m-d H:m:s'),
                'user_id' => 'required',
                'period_id' => 'required',
                'reservation_nights' => 'required|min:1',
            ];
        } else {
            $rules = [
                'reservation_started_at' => 'required|after:' . $periodStartDate->format('Y-m-d H:m:s'),
                'reservation_ended_at' => 'required|before:' . $periodEndDate->format('Y-m-d H:m:s'),
                'user_id' => 'required',
                'period_id' => 'required',
                'reservation_nights' => 'required|min:1',
            ];
        }
        $messages = [
            'before' => '<div class="messagebox">' . trans('reservation.warnings.not_in_period') . '</div>',
            'after' => '<div class="messagebox">' . trans('reservation.warnings.not_in_period') . '</div>',
        ];
        $validator = Validator::make(
            $credentials,
            $rules,
            $messages
        );
        if ($validator->fails()) {
            Session::put('res_error', $validator);
            return Redirect::back()
            ->withErrors($validator);
        }
        // Allright let's save this thing
        $reservation = Reservation::firstOrCreate(['id' => $resID, 'user_id' => $userId, 'period_id' => $currentPeriod->id]);
        $reservation->fill($credentials);
        $reservation->push();
        $existentRes = false;
        if (isset($resID) && !empty($resID)) {
            $existentRes = true;
            $checkChangeStart = new \DateTime($credentials['reservation_started_at']);
            $checkChangeEnd = new \DateTime($credentials['reservation_ended_at']);
            $checkRecordStart = new \DateTime(str_replace('_', '-', $reservation->reservation_started_at));
            $checkRecordEnd = new \DateTime(str_replace('_', '-', $reservation->reservation_ended_at));
            if ($checkChangeStart != $checkRecordStart || $checkChangeEnd != $checkRecordEnd) {
                $reservation->reservation_reminder_sent = 1;
            } else {
                $reservation->reservation_reminder_sent = 0;
            }
        } else {
            $reservation->reservation_reminder_sent = 0;
        }
        //dd(Input::get());
        if (Input::has('show_reservation_guest_started_at_0')) {
            $guestInput = Input::except('_token');
            Guest::createValidateGuestEntries($guestInput, $currentPeriod, $reservation, $existentRes);
        }
        $reservation->push();

        Input::flush();

        return Redirect::back();
    }

    public function saveLocalStorage()
    {
        $args = Input::all();
        if (key_exists('key', $args)) {
            foreach ($args['key'] as $k => $a) {
                $localStorage = LocalStorage::firstOrNew([
                    'local_storage_res_id' => $a['local_storage_res_id'],
                    'local_storage_date' => $a['local_storage_date'],
                    'local_storage_number' => $a['local_storage_number']
                ]);
                $localStorage->push();
            }
        }
    }

    /**
     * Searches reservations for lists
     *
     * @return mixed json
     */
    public function searchAllReservations()
    {
        $reservation = new Reservation();
        if (sizeof(Input::all()) == 0) {
            return view('logged.keeper.reservation')
                ->with('allReservations', $reservation->getReservations());
        }
        return Response::json($reservation->getReservationsAjax(Input::except('resetKeeper')));
    }

    /**
     * Gets a reservation by input->id
     *
     * @return mixed Model
     */
    public function editUserReservation()
    {
        $reservation = new Reservation();
        return $reservation->getReservationsPerDateById(Input::get('res_id'));
    }

    /**
     * Destroys a reservation if it's not billed yet
     *
     * @return array
     */
    public function deleteReservation()
    {
        $set = Setting::getStaticSettings();
        $today = new \DateTime();
        $compDate = new \DateTime(Input::get('reservation_started_at'));
        $today->setTime(0, 0, 0);
        $yesterday = 'no';
        if ($today > $compDate) {
            $yesterday = 'yes';
        }
        $credentials = [
            'before_today' => $yesterday
        ];
        $rules = [
            'before_today' => 'size:2'
        ];
        $messages = [
            'before_today' => 'dfssdfdsfsdfdsfdsf',
        ];
        $validator = Validator::make(
            $credentials,
            $rules,
            $messages
        );
        if ($validator->fails()) {
            return Response::json(['failed' => $validator->messages()]);
        }
        if (Bill::where('reservation_id', '=', Input::get('res_id'))->count() > 0) {
            return Response::json(['failed' => trans('errors.bill_exists')]);
        }
        $reservation = Reservation::where('reservations.id', '=', Input::get('res_id'))
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->select('users.email', 'users.user_fon1', 'users.user_first_name', 'users.user_name', 'reservations.*')
            ->first();
        Guest::where('reservation_id', '=', Input::get('res_id'))->delete();
        $data = [];
        $counter = 0;
        $data[$counter]['address'] = $reservation->user_first_name . ' ' . $reservation->user_name;
        $data[$counter]['to'] = $reservation->email;
        $data[$counter]['fon'] = $reservation->user_fon1;
        $data[$counter]['address'] = $reservation->user_first_name . ' ' . $reservation->user_name;
        $data[$counter]['from'] = \Carbon\Carbon::createFromFormat('Y_m_d', $reservation->reservation_started_at)->formatLocalized(trans('formats.long-date-no-time'));
        $data[$counter]['till'] = \Carbon\Carbon::createFromFormat('Y_m_d', $reservation->reservation_ended_at)->formatLocalized(trans('formats.long-date-no-time'));

        $today->modify('+10 days');
        $diff = intval($today->diff($compDate)->format("%a"));
        if ($diff <= $set->setting_reminder_days) {
            $houseKeeper = User::whereHas('roles', function ($q) {
                $q->where('role_code', '=', 'KP');
            })
                ->get();
            $houseKeeper->each(function ($h) use ($data, $set) {
                Mail::send('emails.reservation_cancelled_reminder_housekeeper', ['data' => $data] + ['address_h' => $h->user_name], function ($message) use ($h, $set) {
                    $message->from($set->setting_app_owner_email, $set->setting_app_owner);
                    $message->to($h->email)->subject($set->setting_app_owner . ': ' . trans('reservation.cancel_res_housekeeper', ['z' => '']));
                });
            });
        }

        $reservation->delete();
        $userRes = new Reservation();

        return $userRes->getAuthReservationsArrayJS();
    }

    /**
     * Saves a new guets on the fly
     *
     */
    public function saveNewGuest()
    {
        $reservation = Reservation::find(Input::get('reserv_id_0'));
        $guest = new Guest();
        $guest->fill(Input::except('_token'));
        $guest->push();
    }

    public function cronReservation()
    {
        $res = new Reservation();
        $set = new Setting();
        return $res->getFutureReservations(true, $set->getSettings());
    }

    /**
     * Destroys a guest on the fly
     *
     * @return array failed/success
     */
    public function deleteGuest()
    {
        $foreignClanGuest = [];
        if (!Input::has('guest_id')) {
            $foreignClanGuest['deleted'] = true;
            return $foreignClanGuest;
        }
        $guest = Guest::find(Input::get('guest_id'));
        if ($guest->role_id == json_decode(Session::get('otherClanRoleId'))->id) {
            $reservation = Reservation::find($guest->reservation_id);
            $reservation->user_id_ab = null;
            $reservation->push();
            $foreignClanGuest['user_id_ab'] = true;
        }
        $guest->delete();
        $foreignClanGuest['deleted'] = true;
        return $foreignClanGuest;
    }

    /**
     * ToDo UNSUED METHOD
     *
     */
    public function putToSession()
    {
        Session::put('localStorage', Input::get('data_value'));
        return Session::get('localStorage');
    }
}
