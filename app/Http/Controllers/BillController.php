<?php

namespace App\Http\Controllers;

use Bill;
use Illuminate\Filesystem\Filesystem;
use User;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Facades\DB;
use Auth;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 11.10.14
 * Time: 22:35
 */

class BillController extends Controller
{

    /**
     * @return mixed
     * @throws \Exception
     */
    public function showBills()
    {
        $bill = new Bill();
        $today = new \DateTime();
        $set = \Setting::getStaticSettings();
        $yearDate = new \DateTime($set->setting_calendar_start);
        $users = User::select('id', 'user_first_name', 'user_name')
        ->orderBy('user_name', 'asc')->get();
        $years = [];
        for($i = 0; $i < $set->setting_calendar_duration; $i++) {
            $years[] = $yearDate->format('Y');
            $yearDate->modify('+ 1 year');
        }
        return view('logged.admin.bill')
            ->with('allBills', $bill->getBillsWithUserReservation())
            ->with('users', $users)
            ->with('years', $years)
            ->with('allTotals', $this->getAllTotals())
            ->with('today', $today->format('Y-m-d'));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function reSendBill()
    {
        $set = \Setting::getStaticSettings();
        $bill_id = request()->input('id');
        $bill = Bill::find($bill_id);
        $user = User::find($bill->bill_user_id);
        $data['billusertext'] = '<b>Wir hoffen, Du hast Deinen Aufenthalt im Palazzin genossen.</b>';
        $data['billusertext'] .= '<p>Wir haben festgestellt, dass folgende Rechnung noch nicht beglichen wurde:<br><b>' . $bill->bill_no . '</b></p>';
        $data['billusertext'] .= '<p>Anbei findest Du Deine Rechnung.</p>';
        $data['billtext'] = $set->setting_bill_text;
        $data['attachment'] = public_path() . $bill->bill_path;
        $user->notify(new \App\Notifications\SendBill($data, $user));
        $bill->bill_resent = 1;
        $bill_resent_at = new \DateTime();
        $bill->bill_resent = 1;
        $bill->bill_resent_date = $bill_resent_at->format('Y-m-d');
        $bill->push();
        return Response::json(['due' => $bill->bill_due, 'resent_data_sort' => $bill_resent_at->format(trans('Y-m-d')), 'resent' => $bill_resent_at->format(trans('formats.short-date-ts')), 'billid' => $bill->id]);
    }

    /**
     * Sets the bill_paid date
     *
     */
    public function payBill()
    {
        $bill = Bill::find(request()->input('id'));
        $bill->bill_paid = request()->input('bill_paid') . ' 00:00:00';
        $bill->bill_due = 0;
        $bill->push();
        $bill_paid_at = new \DateTime($bill->bill_paid);
        return Response::json(['due' => $bill->bill_due, 'paid' => $bill_paid_at->format(trans('formats.short-date-ts')), 'billid' => $bill->id]);
    }
    /**
     * Sets the bill unpaid
     *
     */
    public function unPayBill()
    {
        $bill = Bill::find(Input::get('id'));
        $bill->bill_paid = null;
        $bill->bill_due = 1;
        $bill->push();
        return Response::json(['due' => $bill->bill_due, 'paid' => $bill->bill_paid, 'billid' => $bill->id]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBillFilesList()
    {
        $fs = new Filesystem();
        $files = $fs->allFiles(public_path('/files/__clerk/'));
        //dd($files);
        return view('logged.admin.bill_list')
            ->with('allBills', $files);
    }

    /**
     * @return mixed
     */
    public function getAllTotals ()
    {
        $userBills = '';
        if (!request()->is('admin/bills')) {
            $userBills = ' and bill_user_id = ' . Auth::id();
        }
        $arr['total'] =  number_format(DB::select('select sum(bill_total) as total from bills where bill_sent = 1' . $userBills)[0]->total, 2, '.', '\'');
        $arr['paid'] = number_format(DB::select(DB::raw('select sum(bill_total) as paid from bills where bill_sent = 1 and bill_due = 0' . $userBills))[0]->paid, 2, '.', '\'');
        $arr['unpaid'] = number_format(DB::select(DB::raw('select sum(bill_total) as unpaid from bills where bill_sent = 1 and bill_due = 1' . $userBills))[0]->unpaid, 2, '.', '\'');
        if (request()->ajax()) {
            return Response::json($arr);
        }
        return $arr;
    }

}
