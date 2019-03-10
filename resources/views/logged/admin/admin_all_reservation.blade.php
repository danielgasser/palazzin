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
    <a name="top"></a>
    <div id="reservationInfo">
        <h4></h4>
    </div>
    <div id="upper">
        <table id="table_all_reservations" class="table_all_reservations">
            <thead>
            <tr>
                <th class="0" id="user_name">{{trans('userdata.user_name')}}</th>
                <th class="1" id="arrival">{{trans('reservation.arrival')}}</th>
                <th class="2" id="depart">{{trans('reservation.depart')}}</th>
                <th class="3" id="total_nights">{{trans('reservation.guests.total_nights')}}</th>
                <th class="4" id="total_all_bill">{{trans('bill.total_all_bill')}}</th>
                <th class="5" id="reservation_guest_num">{{trans('validation.attributes.reservation_guest_num')}}</th>
                <th class="6" id="reservation_bill_sent">{{trans('bill.bill_sent')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.no_delete_reservation')
    {{--
     @include('logged.dialog.guest_nan')
     @include('logged.dialog.night_nan')
     @include('logged.dialog.cross_reserv')
     @include('logged.dialog.cross_reserv_user_list')
     @include('logged.dialog.not_invited')
     --}}
@section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script>
        var startDate,
            endDate,
            rolesTaxes = '{!! $roles !!}',
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            datePickersStart = [],
            reservations = JSON.parse('{!!$allRes!!}'),
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            datePickersEnd = [],
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}');
    </script>
    <script src="{{asset('js/V3Reservation.min.js')}}"></script>
    <script src="{{asset('js/events.min.js')}}"></script>
    <script src="{{asset('js/reservation_init.min.js')}}"></script>

@stop
@stop
