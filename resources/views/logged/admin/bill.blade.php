@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>

    <link rel="stylesheet" href="{{asset('libs/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">

    <link rel="stylesheet" href="{{asset('css')}}/bootstrap-datepicker.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <style>
        .dataTables_wrapper {
            margin: 0;
            border: none;
        }
        .form-control {
            width: auto;
        }
    </style>
    @stop
@section('content')
    </div>
    <div class="row" style="border-bottom: 1px solid #b7282e;">
        <div class="col-sm-3 col-md-3 col-sm-6 col-xs-12">
            <h5>Jahr</h5>
            <select id="year" name="year" class="form-control">
                <option value="all">Alle</option>
            @foreach($years as $y) {
                    <option value="{{$y}}">{{$y}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 col-md-3 col-sm-6 col-xs-12">
            <h5>Total<br><span id="total">CHF {{$allTotals['total']}}</span></h5>
        </div>
        <div class="col-sm-3 col-md-3 col-sm-6 col-xs-12">
            <h5>Bezahlt<br><span id="paid">CHF {{$allTotals['paid']}}</span></h5>
        </div>
        <div class="col-sm-3 col-md-3 col-sm-6 col-xs-12">
            <h5>Unbezahlt<br><span id="unpaid">CHF {{$allTotals['unpaid']}}</span></h5>
        </div>
    </div>
    <div class="row">
        <table id="bills">
            <thead>
                <tr>
                    <th></th>
                    <th>{{trans('bill.billno')}}</th>
                    <th>{{trans('bill.date')}}</th>
                    <th>{{trans('bill.total_bill')}}</th>
                    <th>{{trans('profile.names')}}</th>
                    <th>{{trans('bill.paid')}}</th>
                    <th>{{trans('bill.paid_at')}}</th>
                    <th>PDF</th>
                    <th>{{trans('bill.send_bill')}}</th>
                    <th>{{trans('bill.sent_at')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($allBills as $b)
                <tr id="{{$b->id}}">
                    <td></td>
                    <td>{{$b->bill_no}}</td>
                    <td data-sort="{{$b->bill_bill_date}}">{{$b->bill_bill_date_show}}</td>
                    <td data-sort="{{$b->bill_total}}">{{$b->bill_currency}} {{$b->bill_total}}</td>
                    <td data-sort="{{$b->user->user_first_name}} {{$b->user->user_name}}">
                        <a href="{{URL::to('user/profile/' . $b->reservation->user_id)}}">{{$b->user->user_first_name}} {{$b->user->user_name}}</a>
                    </td>
                    <td id="paid_{{$b->id}}">{{(isset($b->bill_paid)) ? trans('bill.paid') : trans('bill.un_paid') }}</td>
                    <td data-sort="{{$b->bill_paid}}" id="datePicker_{{$b->id}}">
                        <input class="form-control input-sm show_reservation" placeholder="{{trans('bill.un_paid')}}" readonly="readonly" name="paidAt_{{$b->id}}" id="paidAt_{{$b->id}}" value="{{$b->bill_paid_show}}" />
                    </td>
                    <td>
                        <a class="bill_path" target="_blank" href="{{URL::to('/')}}{{$b->bill_path}}">{{$b->bill_no}}.pdf</a>
                    </td>
                    <td id="re_send_{{$b->id}}">
                        @if(!isset($b->bill_paid))

                        <button class="btn btn-default"  id="bill_sent_{{$b->id}}">{{trans('bill.send_bill')}}</button>
                            @endif
                    </td>
                    <td id="re_sent_{{$b->id}}" data-sort="{{$b->bill_resent_date}}">{{$b->bill_resent_date_show}}</td>
                </tr>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th>{{trans('bill.billno')}}</th>
                <th>{{trans('bill.date')}}</th>
                <th>{{trans('bill.total_bill')}}</th>
                <th>{{trans('profile.names')}}</th>
                <th>{{trans('bill.paid')}}</th>
                <th>{{trans('bill.paid_at')}}</th>
                <th>PDF</th>
                <th>{{trans('bill.send_bill')}}</th>
                <th>{{trans('bill.sent_at')}}</th>
            </tr>
            </tfoot>
        </table>
    </div>

@section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>

    <script>
        var billPaid = [
                '{{trans('bill.un_paid')}}',
                '{{trans('bill.paid')}}'
            ];

    </script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <script src="{{asset('js/bill_init.min.js')}}"></script>

@stop

@stop
