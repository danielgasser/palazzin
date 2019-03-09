@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp_reservation.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>

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
    <script src="{{asset('assets/js/v3/V3Reservation.js')}}"></script>
    <script src="{{asset('assets/js/v3/events.js')}}"></script>
    <script src="{{asset('assets/js/inits/reservation_init.js')}}"></script>

@stop
@stop
