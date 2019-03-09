@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.js"></script>
    <link rel="stylesheet" href="{{asset('assets/js/v3/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css')}}/bootstrap-datepicker.css"
          rel="stylesheet" media="screen" type="text/css">
    @stop
@section('content')
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
                </tr>
            </thead>
            <tbody>
            @foreach($allBills as $b)
                <tr id="{{$b->id}}">
                    <td></td>
                    <td>{{$b->bill_no}}</td>
                    <td data-sort="{{$b->bill_bill_date}}">{{$b->bill_bill_date_show}}</td>
                    <td data-sort="{{$b->bill_total}}">{{$b->bill_currency}} {{$b->bill_total}}</td>
                    <td>
                        <a href="{{URL::to('user/profile/' . $b->reservation->user_id)}}">{{$b->user->user_first_name}} {{$b->user->user_name}}</a>
                    </td>
                    <td id="paid_{{$b->id}}">{{(isset($b->bill_paid)) ? trans('bill.paid') : trans('bill.un_paid') }}</td>
                    <td data-sort="{{$b->bill_paid}}" id="datePicker_{{$b->id}}">
                        <input class="form-control input-sm show_reservation" placeholder="{{trans('bill.un_paid')}}" readonly="readonly" name="paidAt_{{$b->id}}" id="paidAt_{{$b->id}}" value="{{$b->bill_paid_show}}@" />
                    </td>
                    <td>
                        <a class="bill_path" target="_blank" href="{{URL::to('/')}}{{$b->bill_path}}">{{$b->bill_no}}.pdf</a>
                    </td>
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
            </tr>
            </tfoot>
        </table>
    </div>

@section('scripts')
    @parent

    <script>
        var billPaid = [
                '{{trans('bill.un_paid')}}',
                '{{trans('bill.paid')}}'
            ];

    </script>
    <script src="{{asset('assets/js/inits/bill_init.js')}}"></script>

@stop

@stop
