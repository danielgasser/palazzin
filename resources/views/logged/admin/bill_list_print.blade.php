@extends('layout.master')
@section('content')
{{--Tools::dd($allBills)--}}
<div id="totals" class="table-responsive">
    @if(strpos(URL::to(Route::getCurrentRoute()->getPath()), 'user') === false)
        <h1>{!!trans('navigation.admin/bills')!!}</h1>
    @else
        <h1>{!!trans('navigation.user/bills')!!}</h1>
    @endif
    @if(sizeof($allBills) > 0)
    <table id="bill_all_totals" style="width: 100%;">
        <thead>
            <tr>
                <td colspan="4">
                </td>
            </tr>
        <tr>
            <th class="white-row">{!!trans('bill.currency')!!}</th>
            <th class="white-row">{!!trans('bill.sub_total_all_bill')!!}</th>
            <th class="white-row">{!!trans('bill.taxes')!!}</th>
            <th class="white-row">{!!trans('bill.total_all_bill')!!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th class="white-row" id="bill_currency">{!!$allBills[sizeof($allBills) - 1]->bill_currency!!}</th>
            <th class="white-row" id="bill_subtotals">{!!number_format($allBills[sizeof($allBills) - 1]->subtotals, 2, '.', '\'')!!}</th>
            <th class="white-row" id="bill_taxes">{!!$allBills[sizeof($allBills) - 1]->bill_tax!!} %</th>
            <th class="white-row" id="bill_totals">{!!number_format($allBills[sizeof($allBills) - 1]->totals, 2, '.', '\'')!!}</th>
        </tr>
        <tr>
            <th colspan="5" style="border-top: 4px double #000000;"></th>
        </tr>
        </tbody>
    </table>
        @else
        <h3>{!!trans('errors.no-data', ['n' => 'e', 'd' => 'Rechnungen'])!!}</h3>
@endif
</div>

<div id="after-totals">
    <div class="table-responsive">

        <table id="allReservations" class="table tablesorter">
            @if(sizeof($allBills) > 0)
                {!!$i = 0;!!}
            @foreach($allBills as $b)
            <thead id="heading_{!!$b->id!!}">
                <tr class="keeperDataHead">
                    <th style="height: 62px" class="white-row" colspan="5">{!!str_replace('<br>', ' - ', trans('bill.bill_no'))!!} {!!$b->bill_no!!}</th>
                </tr>
                <tr>
                    <th colspan="4">
                        @if(Request::is('admin/bills'))
                        {!!trans('profile.address')!!}
                        @endif
                    </th>
                    <th>
                        {!!trans('bill.date')!!}
                    </th>
                </tr>
                <tr>
                    @if(Request::is('admin/bills'))
                    <th colspan="2" class="address">
                        {!!$b->user->user_first_name!!} {!!$b->user->user_name!!}<br>
                        {!!$b->user->user_address!!}<br>
                        {!!$b->user->user_zip!!} {!!$b->user->user_city!!}<br>
                        {!!$b->user->country!!}
                    </th>
                    <th colspan="2" class="address">
                        <a href="mailto:{!!$b->user->email!!}">{!!$b->user->email!!}</a><br>
                        {!!$b->user->user_fon1_label!!} {!!$b->user->user_fon1!!}<br>
                        {!!$b->user->user_fon2_label!!} {!!$b->user->user_fon2!!}<br>
                        {!!$b->user->user_fon3_label!!} {!!$b->user->user_fon3!!}<br>
                    </th>
                    @else
                        <th colspan="4" class="address"></th>
                    @endif
                    <th class="white-row address" colspan="1">{!!$b->bill_bill_date_show!!}</th>

                </tr>
                <tr class="keeperDataHead">
                    <th>{!!trans('reservation.arrival')!!}</th>
                    <th>{!!trans('reservation.depart')!!}</th>
                    <th>{!!trans('reservation.nights')!!}</th>
                    <th>{!!trans('reservation.guests.title')!!}</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody id="keeperData_{!!$b->id!!}">
                <tr>
                     <td>{!!$b->reservation->reservation_started_at_show!!}</td>
                     <td id="currentCalDate_{!!$b->reservation_started_at!!}">{!!$b->reservation->reservation_ended_at_show!!}</td>
                     <td>{!!$b->reservation->reservation_nights!!}</td>
                     <td>
                        <table class="table" id="allGuests_{!!$b->id!!}">
                            <thead>
                                <tr>
                                    <th>{!!trans('reservation.arrival')!!}</th>
                                    <th>{!!trans('reservation.depart')!!}</th>
                                    <th>{!!trans('reservation.guests.number')!!}</th>
                                    <th>{!!trans('reservation.guests.role')!!}</th>
                                    <th>{!!trans('reservation.guests.tax_night')!!}</th>
                                    <th>{!!trans('reservation.nights')!!}</th>
                                    <th>{!!trans('reservation.guests.total')!!}</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($b->reservation->guests as $guest)
                                <tr>
                                    <td>{!!$guest->guest_started_at_show!!}</td>
                                    <td>{!!$guest->guest_ended_at_show!!}</td>
                                    <td>{!!$guest->guest_number!!}</td>
                                    <td>{!!$guest->role_code!!}</td>
                                    <td>{!!$guest->role_tax_night!!}</td>
                                    <td>{!!$guest->guest_night!!}</td>
                                    <td>{!!number_format($guest->guestSum, 2, '.', '\'')!!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <a target="_blank" href="{!!URL::to('/')!!}{{$b->bill_path}}">{!!$b->bill_no!!}.pdf</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table id="bill_totals" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="white-row">{!!trans('bill.currency')!!}</th>
                                    <th class="white-row">{!!trans('bill.sub_total_bill')!!}</th>
                                    <th class="white-row">{!!trans('bill.taxes')!!}</th>
                                    <th class="white-row">{!!trans('bill.total_bill')!!}</th>
                                    <th class="white-row">{!!trans('bill.paid')!!}</th>
                                    <th colspan="2" class="white-row">{!!trans('bill.paid_at')!!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="white-row">{!!$b->bill_currency!!}</td>
                                    <td class="white-row">{!!$b->bill_sub_total!!}</td>
                                    <td class="white-row">{!!$b->bill_tax!!} %</td>
                                    <td class="white-row">{!!$b->bill_total!!}</td>
                                    <td class="white-row" id="paid_or_not_{!!$i!!}">{!!($b->bill_due == 1) ? trans('dialog.n') : trans('dialog.y')!!}</td>
                                    <td colspan="2" class="white-row" id="when_paid_{!!$i!!}">
                                        @if($b->bill_due == 1 && Request::is('admin/bills'))
                                        {!!Form::model($b, array('url' => 'admin/bills/paid', 'class' => '', 'id' => 'bill_paid_form_' . $i, 'files' => true))!!}
                                        {!!Form::input('text', 'bill_paid', null, array('class' => 'form-control date_type', 'id' => 'bill_paid_' . $i, 'data_id' => $b->id, 'readonly' => 'readonly'))!!}
                                        <button class="btn btn-default" id="savePaid_{!!$b->id!!}_{!!$i!!}">{!!trans('dialog.save')!!}</button>
                                        {!!Form::close()!!}
                                        @else
                                        {!!$b->bill_paid_show!!}
                                            <br><button class="btn btn-default" id="undoSavePaid_{!!$b->id!!}_{!!$i!!}">{!!trans('dialog.reset')!!}</button>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="border-top: 4px double #fff;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                {!!$i++!!}
             @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
    @section('scripts')
    @parent
    <script>
        var autid = '{!!Auth::id()!!}';
    </script>
        <script src="{!!asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')!!}"></script>
        <script src="{!!asset('assets/js/inits/bill_init.js')!!}"></script>
        <script src="{!!asset('assets/js/inits/search_bill_tables_init.js')!!}"></script>
        <script >
            var ss = 'ASC',
                a = '#allReservations',
                locale = '{!!Lang::get('formats.langlangjs')!!}',
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                cols = $('th'),
                langProfile = {!!json_encode(Lang::get('profile'))!!},
                langRes = {!!json_encode(Lang::get('reservation'))!!},
                langBill = {!!json_encode(Lang::get('bill'))!!},
                langError = {!!json_encode(trans('errors.no-data', ['n' => 'e', 'd' => 'Rechnungen']))!!},
                yl = [],
                pay_yesno = ['{!!trans('dialog.y')!!}', '{!!trans('dialog.n')!!}'],
                settings = {!!App::make('GlobalSettings')->getSettings()!!},
                ml = [],
                route = '{!!Route::getCurrentRoute()->getPath()!!}',
                usersList = [{!!json_encode($users)!!}],
                billPaid = $('[name="_token"]').val();
        </script>
    @stop

@stop
