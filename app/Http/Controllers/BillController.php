<?php

namespace App\Http\Controllers;

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
        $users = User::select('id', 'user_first_name', 'user_name')
        ->orderBy('user_name', 'asc')->get();
        return view('logged.admin.bill')
            ->with('allBills', $bill->getBillsWithUserReservation())
            ->with('users', $users)
            ->with('today', $today->format('Y-m-d'));
    }

    /**
     * Search bills
     *
     * @return mixed json
     */
    public function searchAllBills()
    {
        $bill = new Bill();
        return $bill->getBillsAjax(Input::except('resetKeeper'));
    }

    /**
     * Search bills
     *
     * @return mixed json
     */
    public function searchByBillNo()
    {
        $bill = new Bill();
        return $bill->getBillsNoAjax(Input::get('bill_no'));
    }

    /**
     * Search bills
     *
     * @return mixed json
     */
    public function searchAllBillsUser()
    {
        $bill = new Bill();
       // Tools::dd(Input::all(), true);

        return Response::json($bill->getBillsAjaxUser(Input::except('resetKeeper')));
    }

    /**
     * Generates bills
     * Route = admin/bills/generate
     * ToDo admin/bills/generate as Cron Job
     *
     */
    public function generateBills()
    {
        $b = new Bill();
        // ToDo add settings param
        if (!$b->generateBills()) {
            abort(403);
        }
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

    public function downloadBills($filename)
    {
        dd('ff', $filename);
        return $filename;
        $headers = [
            'Content-Type' => 'application/pdf'
        ];
        return \Response::download($filename, 200, $headers);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function cronBills()
    {
        $bill = new Bill();
        return $bill->generateBills();
    }
}
