<?php

namespace App\Http\Controllers;

use Bill;
use LoginStat;
use Mpdf\Mpdf;
use Reservation;
use Illuminate\Support\Facades\Input;
use Response;
use Request;

class StatsController extends Controller
{

    public function showStatsMenu()
    {
        return view('logged.statistics.stats_menu');
    }

    public function showStatsReservationsCalendarPerMonth()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerMonth();
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.statistics.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsNightsTotal()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerFamilyNightsTotal(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.statistics.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsNightsTotalGuests()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerGuestNightsTotal(Input::get('year'));
        if(Request::ajax()) {
            return Response::json($res);
        }

        return View::make('logged.statistics.stats_calendar')
            ->with('allReservations', $res);

    }

    public function showStatsReservationsCalendarTotalPerDay()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerDayTotal(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.statistics.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsReservations()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.statistics.stats')
            ->with('allReservations', $res);
    }

    public function showStatsReservationsCalendar()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsCalendar(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.statistics.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsBills()
    {
        $bill = new Bill();
        $res = $bill->getBillsStatsCalendar(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
        return view('logged.statistics.stats_bill')
            ->with('allReservations', $res);
    }

    public function showStatsBillsTotal()
    {
        $bill = new Bill();
        $res = $bill->getBillsTotalStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
        return view('logged.statistics.stats_bill')
            ->with('allReservations', $res);
    }

    public function showStatsBillsTotalPerYear()
    {
        $bill = new Bill();
        $billPaid = $bill->getBillsTotalStatsPerYear([request()->input('year') . '-%']);
        if (Request::ajax()) {
            return Response::json($billPaid);
        }
        return view('logged.statistics.stats_bill')
            ->with('allReservations', $billPaid);
    }

    public function showStatsLogin()
    {
        if (Input::get('year') == null) {
            return view('logged.statistics.stats_login')
                ->with('allReservations', '');
        }
        $logins = new LoginStat();
        $res = $logins->filterLoginsStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
    }
}
