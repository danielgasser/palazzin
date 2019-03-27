<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bill extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'bills';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'bill_total',
        'bill_sub_total',
        'reservation_id',
        'bill_due',
        'bill_bill_date',
        'bill_path',
        'bill_currency',
        'bill_tax',
        'bill_no',
        'bill_user_id',
        'bill_resent_date',
        'bill_resent'
    );

    /**
     *
     * @return mixed
     */
    public function guests() {
        return $this->hasMany('Guest');
    }

    /**
     *
     * @return mixed
     */
    public function reservations() {
        return $this->belongsTo('Reservation');
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function generateBills() {
        $set = Setting::getStaticSettings();
        $today = new \DateTime();
        $today->modify('+1 day');
        $reservations = new Reservation();

        $reservations = $reservations
            ->where('reservation_ended_at', '<', $today->format('Y-m-d') . ' 00:00:00')
            ->where('reservation_bill_sent', '=', '0')
            ->with(array(
                'guests' => function ($q) {
                    $q->select('guests.id', 'reservation_id', 'guest_number', 'guest_night', 'guest_ended_at', 'guest_started_at', 'role_id');
                },
            ))
            ->get();
        $arr = array();
        $overSeaUser = false;
        $reservations->each(function ($r) use ($arr, $set, $overSeaUser) {
            $userRoles = \User::getRolesByID($r->user_id);
            $r->guests->each(function ($g) use ($arr, $r, $userRoles) {
                $g->calcGuestSumTotals();
                $r->reservation_sum += (float)$g->guestSum;
            });
            foreach($userRoles as $u) {
                if($u->role_code == 'GU'){
                    $role = Role::getRoleByRoleCode('GU');
                    $r->reservation_sum += $r->reservation_nights * $role['role_tax_night'];
                    $r->guests[0]->guest_number += 1;
                    break;
                }
            }
            if ((float)$r->reservation_sum > 0) {
                $credentials = [
                    'bill_sub_total' => $r->reservation_sum,
                    'bill_total' => $r->reservation_sum + round($r->reservation_sum / 100 * intval($set->setting_global_tax), 1),
                    'reservation_id' => $r->id,
                    'bill_bill_date' => str_replace('_', '-', $r->reservation_ended_at . ' 00:00:00'),
                    'bill_path' => '/',
                    'bill_tax' => 0,
                    'bill_currency' => 'CHF',
                    'bill_no' => '',
                    'bill_user_id' => $r->user_id
                ];

                $bill = self::firstOrCreate($credentials);
                $bill->bill_due = (isset($bill->bill_due)) ? $bill->bill_due : 1;
                $bill->push();

                $r->reservation_sum = 0;
                $userForBill = \User::find($r->user_id);
                $path = public_path() . '/files/' . str_replace('.', '_', $userForBill->user_login_name);
                if (file_exists($path) === false) {
                    mkdir($path);
                }
                chmod($path, 0777);
                $billDate = new \DateTime($bill->bill_bill_date);
                $userForBillCountry = DB::select(DB::raw('select `country_name_' . trans('formats.langjs') . '` from countries where country_code = ' . $userForBill['user_country_code']));
                $billPDFNo = 'No-' . substr($bill->id . '-' . str_replace([' ', ':', '-', '.'], '', $bill->bill_bill_date), 0, -6);
                $resStart = date("d.m.Y", strtotime($r->reservation_started_at));
                $resEnd = date("d.m.Y", strtotime($r->reservation_ended_at));
                $pdfTitle = $set->setting_site_name . '-' . trans('bill.bill') . '-' . $billPDFNo . '.pdf';

                $bill->bill_path = '/files/' . str_replace('.', '_', $userForBill['user_login_name']) . '/' . $set->setting_site_name . '-' . trans('bill.bill') . '-' . $billPDFNo . '.pdf';
                $bill->bill_tax = $set->setting_global_tax;
                $bill->bill_currency = $set->setting_currency;
                $bill->bill_no = $billPDFNo;
                $bill->bill_user_id = $r->user_id;
                $arr = array(
                    'resBill' => $r,
                    'userBill' => $userForBill,
                    'bill' => $bill,
                    'billDate' => $billDate->format(trans('formats.short-date-ts')),
                    'billId' => $billPDFNo,
                    'resDate' => array($resStart, $resEnd),
                    'currency' => $bill->bill_currency,
                    'tax' => $bill->bill_tax,
                    'billusertext' => $set->setting_bill_mail_text,
                    'billtext' => $set->setting_bill_text,
                    'setting_bill_deadline' => $set->setting_bill_deadline,
                    'billAddressCountry' => $userForBillCountry[0]->{'country_name_' . trans('formats.langjs')},
                    'baseUrl' => $set->setting_site_url,
                    'settings' => $set,
                    'url' => 'https://palazzin.ch'
                );
                $data = [
                    'bill' => $arr,
                    'settings' => $set,
                    'url' => 'https://palazzin.ch'
                ];
                $view = view('pdfs.bill')
                    ->with('bill', $data)
                    ->with('settings', $set)
                    ->with('url', URL::to('/'));
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('pdfs.bill', $data);
                $pdf->save(public_path() . '/files/__clerk/' . $pdfTitle)
                    ->stream($pdfTitle);
                $pdf->save($path . '/' . $pdfTitle)
                    ->stream($pdfTitle);
                //mail
                $arr['attachment'] = public_path() . '/files/__clerk/' . $pdfTitle;
                if (intval($bill->bill_sent) == 0) {
                    $userForBill->notify(new \App\Notifications\SendBill($arr, $userForBill));
                    $bill->bill_sent = 1;
                }
                $bill->push();
            }
            unset($r->reservation_sum);
            $r->reservation_bill_sent = 1;
            $r->save();
        });
        return var_dump($reservations->toArray());
    }

    /**
     * Gets all bills with user, reservation, guests
     *
     * @return mixed Collection
     */
    public function getBillsWithUserReservation () {
        if (strpos(Route::getFacadeRoot()->current()->uri(), 'admin') !== false) {
            $bills = self::select('*')->orderBy('bill_bill_date', 'DESC')->get();
        } else {

            $bills = self::where('bill_user_id', '=', Auth::id())->get();
        }
        $bills->each(function ($b) {
            global $subtotals;
            global $totals;
            $r = new Reservation();
            $b->reservation = $r->where('id', '=', $b->reservation_id)
                ->select('id', 'reservation_started_at', 'reservation_ended_at', 'reservation_nights', 'user_id')
                ->with(array(
                    'guests' => function ($q) {
                        $q->select('guests.id', 'reservation_id', 'guest_number', 'guest_night', 'guest_ended_at', 'guest_started_at', 'role_id');
                    },
                ))
                ->first();
            $b->user = \User::find($b->reservation->user_id);
            $b->reservation->guests->each(function ($g) use($b) {
                $g->calcGuestSumTotals();
                $s = new DateTime($g->guest_started_at);
                $g->guest_started_at_show = $s->format('d. m Y');
                $e = new DateTime($g->guest_ended_at);
                $g->guest_ended_at_show = $e->format('d. m Y');
                $b->reservation->reservation_sum += $g->guestSum;
            });
            $bds = new \DateTime($b->bill_bill_date);
            $s = new DateTime($b->reservation->reservation_started_at);
            $b->reservation->reservation_started_at_show = $s->format('d. m Y');
            $e = new DateTime($b->reservation->reservation_ended_at);
            $b->reservation->reservation_ended_at_show = $e->format('d. m Y');
            $b->bill_bill_date_show = $bds->format(trans('formats.short-date-ts'));
            if (isset($b->bill_paid)) {
                $bds = new \DateTime($b->bill_paid);
                $b->bill_paid_show = $bds->format(trans('formats.short-date-ts'));
            }

            if (isset($b->bill_resent_date)) {
                $bds = new \DateTime($b->bill_resent_date);
                $b->bill_resent_date_show = $bds->format(trans('formats.short-date-ts'));
            }

            $ubc = DB::select(DB::raw('select `country_name_' . trans('formats.langjs') . '` from countries where country_code = ' . $b->user->user_country_code));
            $b->user->country = $ubc[0]->{'country_name_' . trans('formats.langjs')};
            $subtotals += (float)$b->bill_sub_total;
            $totals += (float)$b->bill_total;
            $b->subtotals = $subtotals;
            $b->totals = $totals;
        });
        return $bills;
    }

    /**
     * @param array $year
     * @return array
     */
    public function getBillsStatsCalendar($year = array('2015-%'))
    {
        if($year == NULL) {
            $year = array('%');
        }
        $bills = $this
            ->join('reservations', 'reservations.id', '=', 'bills.reservation_id')
            ->select('reservations.reservation_started_at',
                    'bills.bill_bill_date',
                    'bills.bill_paid',
                    'bills.bill_path',
                    'bills.bill_no',
                    'bills.bill_currency',
                    'bills.bill_total')
            ->where('bills.bill_bill_date', 'like', $year[0])
            ->orWhere(function ($query) use($year) {
                foreach($year as $y){
                    $query->orWhere('bills.bill_bill_date', 'like', $y);
                }
            })
            ->where('bills.bill_sent', '=', '1')
            ->orderBy('reservations.reservation_started_at', 'asc')
            ->get();
        if($bills->isEmpty()) return array();

        $bills->each(function($b) {
            $s = new \DateTime(str_replace('_', '-', $b->reservation_started_at));
            $t = new \DateTime(str_replace('_', '-', $b->bill_bill_date));

            $b->reservation_started_at_show = $s->format(trans('formats.short-date-ts'));
            $b->bill_bill_date_show = $t->format(trans('formats.short-date-ts'));
            $b->bill_bill_year = $t->format(trans('Y'));
            if ($b->bill_paid != null){
                $u = new \DateTime(str_replace('_', '-', $b->bill_paid));
                $b->bill_paid_show = $u->format(trans('formats.short-date-ts'));
            }
        });
        return $bills;
    }

    /**
     * @param array $year
     * @return array
     */
    public function getBillsTotalStats($year = array('2015-%'))
    {
        $totals = array();
        $bills = $this
            ->select(
                'bills.bill_bill_date',
                'bills.bill_paid',
                'bills.bill_currency',
                'bills.bill_total')
            ->where('bills.bill_bill_date', 'like', $year[0])
            ->where('bills.bill_sent', '=', '1')
            ->orWhere(function ($query) use($year) {
                foreach($year as $y){
                    $query->orWhere('bills.bill_bill_date', 'like', $y);
                }
            })
            ->get();
        if($bills->isEmpty()) return array();
        $bills->bill_total_paid = 0;
        $bills->bill_total = 0;
        $bills->bill_currency = $bills[0]->bill_currency;
        $bills->year_paid = array();
        $bills->year_unpaid = array();
        $bills->year_total = array();
        $bills->each(function($b) use($bills) {
            $t = new \DateTime($b->bill_bill_date);
            if($b->bill_paid != null) {
                if (isset($bills->year_paid[$t->format('Y')])){
                    $bills->year_paid[$t->format('Y')] += $b->bill_total;
                } else {
                    $bills->year_paid[$t->format('Y')] = $b->bill_total;
                }
            } else {
                if (isset($bills->year_unpaid[$t->format('Y')])){
                    $bills->year_unpaid[$t->format('Y')] += $b->bill_total;
                } else {
                    $bills->year_unpaid[$t->format('Y')] = $b->bill_total;
                }
            }
            if (isset($bills->year_total[$t->format('Y')])){
                $bills->year_total[$t->format('Y')] += $b->bill_total;
            } else {
                $bills->year_total[$t->format('Y')] = $b->bill_total;
            }
        });
        $totals['totals']['unpaid'] = 0;
        foreach($bills->year_unpaid as $key => $total){
            $totals[$key]['unpaid'] = $total;
            $totals[$key]['unpaid_show'] = number_format($total, 2, '.', '\'');
            $totals['totals']['unpaid'] += $total;
        }
        $totals['totals']['paid'] = 0;
        foreach($bills->year_paid as $key => $total){
            $totals[$key]['paid'] = $total;
            $totals[$key]['paid_show'] = number_format($total, 2, '.', '\'');
            $totals['totals']['paid'] += $total;
        }
        $totals['totals']['total'] = 0;
        foreach($bills->year_total as $key => $total){
            $totals[$key]['total'] = $total;
            $totals[$key]['total_show'] = number_format($total, 2, '.', '\'');
            $totals['totals']['total'] += $total;
        }
        $totals['totals']['paid_show'] = number_format($totals['totals']['paid'], 2, '.', '\'');
        $totals['totals']['unpaid_show'] = number_format($totals['totals']['unpaid'], 2, '.', '\'');
        $totals['totals']['total_show'] = number_format($totals['totals']['total'], 2, '.', '\'');
        $totals['currency'] = $bills->bill_currency;
        return $totals;
    }

    /**
     * @param array $year
     * @param null  $user_id
     * @return array
     */
    public function getBillsTotalStatsPerYear($year = array('2015-%'), $user_id = null)
    {
        if ($user_id === null) {
            $bills = $this->selectRaw("bill_bill_date, bill_due, bill_total, bill_bill_date")
                ->where('bill_bill_date', 'like', $year[0])
                ->where('bills.bill_sent', '=', '1')
                ->orWhere(function ($query) use($year) {
                    foreach($year as $y){
                        $query->orWhere('bill_bill_date', 'like', $y);
                    }
                })
                ->get();
        } else {
            $bills = $this->selectRaw("bill_bill_date, bill_due, bill_total, bill_bill_date")
                ->join('users', function ($join) use ($user_id) {
                    $join->on('users.id', '=', 'bills.bill_user_id')
                        ->where('bills.bill_user_id', '=', $user_id);
                })
                ->where('bill_bill_date', 'like', $year[0])
                ->where('bills.bill_sent', '=', '1')
                ->where('bills.bill_user_id', '=', $user_id)
                ->orWhere(function ($query) use($year) {
                    foreach($year as $y){
                        $query->orWhere('bill_bill_date', 'like', $y);
                    }
                })
                ->get();
        }
        $totals = array();
        $bills->month_total = [];
        $bills->year_total = [];
        $bills->month_total_unpaid = [];
        $bills->each(function ($b) use($bills) {
            $bm = new \DateTime($b->bill_bill_date);
            if($b->bill_due == '1') {
                if (isset($bills->month_total['unpaid'][$bm->format('Y_m')])) {
                    $bills->month_total['unpaid'][$bm->format('Y_m')] += $b->bill_total;
                } else {
                    $bills->month_total['unpaid'][$bm->format('Y_m')] = $b->bill_total;
                }
                if (isset($bills->year_total['unpaid'][$bm->format('Y')])) {
                    $bills->year_total['unpaid'][$bm->format('Y')] += $b->bill_total;
                } else {
                    $bills->year_total['unpaid'][$bm->format('Y')] = $b->bill_total;
                }
            } else {
                if (isset($bills->month_total['paid'][$bm->format('Y_m')])) {
                    $bills->month_total['paid'][$bm->format('Y_m')] += $b->bill_total;
                } else {
                    $bills->month_total['paid'][$bm->format('Y_m')] = $b->bill_total;
                }
                if (isset($bills->year_total['paid'][$bm->format('Y')])) {
                    $bills->year_total['paid'][$bm->format('Y')] += $b->bill_total;
                } else {
                    $bills->year_total['paid'][$bm->format('Y')] = $b->bill_total;
                }
            }
            if (isset($bills->month_total['total'][$bm->format('Y_m')])) {
                $bills->month_total['total'][$bm->format('Y_m')] += $b->bill_total;
            } else {
                $bills->month_total['total'][$bm->format('Y_m')] = $b->bill_total;
            }
            if (isset($bills->year_total['total'][$bm->format('Y')])) {
                $bills->year_total['total'][$bm->format('Y')] += $b->bill_total;
            } else {
                $bills->year_total['total'][$bm->format('Y')] = $b->bill_total;
            }
        });
        foreach($bills->month_total as $key => $total){
            foreach($total as $k => $t){
                $totals[$key][$k] = number_format($t, 2, '.', '\'');
            }
        }
        foreach($bills->year_total as $key => $total){
            foreach($total as $k => $t){
                $totals[$key][$k] = number_format($t, 2, '.', '\'');
            }
        }
        return $totals;
    }
}
