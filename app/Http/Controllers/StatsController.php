<?php

namespace App\Http\Controllers;

use Bill;
use LoginStat;
use Reservation;
use Illuminate\Support\Facades\Input;
use Response;
use Request;

class StatsController extends Controller
{

    public function showStatsMenu()
    {
        return view('logged.admin.stats_menu');
    }

    public function showStatsReservationsCalendarPerMonth()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerMonth();
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsNightsTotal()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerFamilyNightsTotal(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsNightsTotalGuests()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerGuestNightsTotal(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsReservationsCalendarTotalPerDay()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsPerDayTotal(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsReservations()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats')
            ->with('allReservations', $res);
    }

    public function showStatsReservationsCalendar()
    {
        $reservation = new Reservation();
        $res = $reservation->getReservationsStatsCalendar(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }

        return view('logged.admin.stats_calendar')
            ->with('allReservations', $res);
    }

    public function showStatsBills()
    {
        $bill = new Bill();
        $res = $bill->getBillsStatsCalendar(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
        return view('logged.admin.stats_bill')
            ->with('allReservations', $res);
    }

    public function showStatsBillsTotal()
    {
        $bill = new Bill();
        $res = $bill->getBillsTotalStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
        return view('logged.admin.stats_bill')
            ->with('allReservations', $res);
    }

    public function showStatsBillsTotalPerYear()
    {
        $bill = new Bill();
        $billPaid = $bill->getBillsTotalStatsPerYear(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($billPaid);
        }
        return view('logged.admin.stats_bill')
            ->with('allReservations', $billPaid);
    }

    public function showStatsLogin()
    {
        if (Input::get('year') == null) {
            return view('logged.admin.stats_login')
                ->with('allReservations', '');
        }
        $logins = new LoginStat();
        $res = $logins->filterLoginsStats(Input::get('year'));
        if (Request::ajax()) {
            return Response::json($res);
        }
    }

    public function printStats()
    {
        $html = '<style>
            body, html {
                font-family: "Ubuntu",sans-serif !important;
                font-size: 14px;
            }';
        $html .= '.highcharts-axis {
                font-size: 14px !important;
            }';
        $html .= 'a {
                color: #dfb20d;
            }';
        $html .= '#mPDF_Print {
                background-color: #ffffff !important;
                         font-size: 14px !important;
            }';
        //    body{
        //        background-color:white !important
        //    }
        //    .highcharts-axis{
        //        font-size:16px;
        //        padding:0.2em
        //    }
        $html .= 'th{
                font-size:22px !important;
                font-weight: bolder
            }';
        $html .= 'td{
                font-size:16px !important;
                padding: 2px;
            }';
        $html .= 'h6 {
                color: #000000;
                font-size: 14px !important;
                font-weight: bold;
                display: block;
                 line-height: 22px;
           }';
        $html .= 'tspan {
                line-height: 22px;
            }';
        $html .= '.total{
                 color:#df7015 !important;
                 width:8%
             }
             .paid{
                 color:#169227 !important
             }
             .unpaid{
                 color:#922825 !important
             }
             .highcharts-tooltip {
                display:none !important
             }';
             //#footer{
             //    display:none !important
             //}
        $html .= '</style>';
        $stylesheet = file_get_contents(public_path() . '/assets/css/stats.css');
        $stylesheet .= file_get_contents(public_path() . '/assets/css/stats_print.css');
        $html .= Input::get('html');
        $mPdf = new mPDF('utf-8', 'A4-' . Input::get('dir'), 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $mPdf->SetDisplayMode('fullpage');
        $mPdf->setHTMLHeader('<div style="font-size: 12pt;text-align: center; font-weight: bold; color: #000000">' . Input::get('title') . '</div>');
        $mPdf->keep_table_proportions = true;
        $mPdf->tableMinSizePriority = true;
        $mPdf->DefHTMLFooterByName('Footer', '<div style="width: 100%; border-top: 1px solid #333333"></div><div style="font-size: 10pt; float: left; width: 49%">
            RoomApp © created by <a target="_blank" href="https://toesslab.ch/">toesslab - websolutions</a>
            </div>
            <div style="font-size: 10pt; float: right; width: 49%; text-align: right">Charts by <a target="_blank" href="http://www.highcharts.com/">HIGHCHARTS</a></div></div>');
        $mPdf->SetHTMLFooterByName('Footer', 'O');
        //$mPdf->SetHTMLHeaderByName('Header', 'O');
        //$mPdf->SetDefaultBodyCSS('font-size', '22px');
        $mPdf->CSSselectMedia = 'mpdf';
        $mPdf->WriteHTML($stylesheet, 1);
        $mPdf->WriteHTML($html, 0);
        $mPdf->Output(public_path() . '/files/__stats/' . Input::get('filename') . '.pdf');
        return '/files/__stats/' . Input::get('filename') . '.pdf';
    }
    /**
     * Shows all bills
     *
     * @return mixed View
     */
    public function showStatsPrint()
    {
        $allPdfs = [];
        $path = public_path() . '/files/__stats/';
        $pdfs = scandir($path);
        $i = 0;
        foreach ($pdfs as $p) {
            if (is_file($path . $p)) {
                $allPdfs[$i]['link'] = 'https://' . $_SERVER['SERVER_NAME'] . '/public/files/__stats/' . $p;
                $allPdfs[$i]['name'] = $p;
            }
            $i++;
        }
        return view('logged.admin.stats_list')
            ->with('allBills', $allPdfs);
    }
}
