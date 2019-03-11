<?php

namespace App\Http\Controllers;

use App\Notifications\ReservationNotification;
use Bill;
use Illuminate\Filesystem\Filesystem;
use User;
use Illuminate\Support\Facades\Input;
use Response;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 11.10.14
 * Time: 22:35
 */

class CronController extends Controller
{
    protected $setting;

    public function __construct()
    {
        $this->middleware('guest');
        $setting = \Illuminate\Support\Facades\App::make(\Setting::class);
        $this->setting = $setting::getStaticSettings();
    }

    /**
     * @throws \Exception
     */
    public function sendBirthdayNotification () {
        $today = new \DateTime();
        $today->setTime(0, 0, 0, 0);
        $users = User::whereDay('user_birthday', '=', $today->format('d'))
            ->whereMonth('user_birthday', '=', $today->format('m'))
            ->get();
        $users->each(function ($u) {
            $u->notify(new \App\Notifications\HappyBirthday($u));
        });
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function sendBill()
    {
        $bill = new Bill();
        return $bill->generateBills();
    }

    /**
     * @param $sendToHousekeeper
     * @return array
     * @throws \Exception
     */
    public function getFutureReservations ($sendToHousekeeper = false) {
        $sendToHousekeeper = ($sendToHousekeeper === '1') ? true : false;
        $today = new \DateTime();
        // test
        $today->setDate(2019, 6, 2);
        $tomorrow = new \DateTime();
        $set = $this->setting;
        // test
        $tomorrow->setDate(2019, 6, 2);
        $tomorrow->modify('+' . $set->setting_reminder_days . ' day');
        $today->setTime(0, 0, 0);
        $tomorrow->setTime(0, 0, 0);
        $data = [];
        if ($sendToHousekeeper) {
            $houseKeeper = \User::find(32);
        }
        $res = \Reservation::whereBetween('reservation_started_at', [$today->format('Y-m-d') . ' 00:00:00', $tomorrow->format('Y-m-d') . ' 00:00:00'])
            ->where('reservation_reminder_sent', '=', '0')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            ->with('guests')
            ->select('users.email', 'users.user_fon1', 'users.user_first_name', 'users.user_name', 'reservations.*')
            ->get();
        $total = $res->count();
        $res->each(function ($r, $k) use(&$data, $set, $today)  {
            $arr = [];
            $data[$k]['id'] = $r->id;
            $data[$k]['uid'] = $r->user_id;
            $data[$k]['address'] = $r->user_first_name . ' ' . $r->user_name;
            $data[$k]['message_text'] = $set->setting_start_reservation_mail_text;
            $data[$k]['to'] = $r->email;
            $r->guests->each(function ($g) use ($k, $data, &$arr) {
                $arr[] = '<li><b>' . $g->guest_title . '</b></li>';
            });
            $data[$k]['guests'] = implode('', $arr);
            $data[$k]['fon'] = $r->user_fon1;
            $data[$k]['from'] = date("d.m.Y", strtotime($r->reservation_started_at));
            $data[$k]['till'] = date("d.m.Y", strtotime($r->reservation_ended_at));
            $r->reservation_reminder_sent = 1;
            $r->reservation_reminder_sent_at = $today->format('Y-m-d') . ' 00:00:00';
            $r->save();
        });
        $errors = [];
        foreach($data as $r) {
            $u = User::find($r['uid']);
            $u->notify(new ReservationNotification($u, $r));
        }
        if ($sendToHousekeeper) {
            $data['hk']['address'] = $houseKeeper->user_first_name . ' ' . $houseKeeper->user_name;
            $data['hk']['to'] = $houseKeeper->email;
            $data['hk']['message_text'] = trans('reservation.begin_res_housekeeper', ['z' => $total]);
            if ($sendToHousekeeper) {
                $houseKeeper->notify(new ReservationNotification($houseKeeper, $r));
            }
        }

        $data['errors'] = $errors;
        return $data;
    }


}
