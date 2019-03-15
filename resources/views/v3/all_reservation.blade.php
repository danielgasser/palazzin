@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" href="{{asset('css/reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" href="{{asset('css/all_reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" href="{{asset('css/new_reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/datatables_roomapp_reservation.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>


@stop
@section('content')
    <a name="top"></a>
    <div id="reservationInfo">
        <h4></h4>
    </div>
    <div id="container_table_all_reservations" class="table-responsive">
            <table id="table_all_reservations" class="table">
                @if(sizeof($userRes) > 0)
                <thead>
                <tr>
                    <th scope="col" class="0" id="edit"></th>
                    <th scope="col" class="1" id="arrival">{{trans('reservation.arrival_departure')}}</th>
                    <th scope="col" class="3" id="total_nights">{{trans('reservation.guests.nights')}}</th>
                    <th scope="col" class="4" id="total_all_bill">{{trans('bill.total_all')}}</th>
                    <th scope="col" class="6" id="guests"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($userRes as $key => $res)
                        @php
                        $class = ($key % 2 === 0) ? 'even' : 'odd';
                        @endphp
                    <tr class="{{$class}}" id="delete_table_all_reservations_{{$res->id}}">
                        <td>
                            @if($res->editable)
                                <a style="width: 50px" title="{{trans('dialog.edit')}}" class="btn btn-danger btn-v3 show_reservation" id="edit_reservation_{{$res->id}}" href="{{  route('edit_reservation', ['id' => $res->id])  }}"><i class="fas fa-edit"></i></a>
                            <form style="display: inline-block" id="delete_table_all_reservations_{{$res->id}}" method="post" action="{{  route('delete_reservation', ['id' => $res->id])  }}">
                                {{ csrf_field() }}
                                <button style="width: 50px" title="{{trans('dialog.delete')}}" class="btn btn-danger btn-v3 show_reservation" id="delete_reservation_{{$res->id}}"><i class="fas fa-trash"></i></button>
                            </form>
                                @endif
                        </td>
                        <td>{{$res->reservation_started_at}}-{{$res->reservation_ended_at}}</td>
                        <td>{{$res->reservation_nights}}</td>
                        <td>{{$res->sum_total}}</td>
                        <td scope="col" class="6" id="guests">
                            @if(sizeof($res->guests) > 0)
                                <b>{{$res->sum_guest}} {{trans('reservation.guests.title')}}</b>
                                <table id="guestTable">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>{{trans('reservation.arrival_departure')}}</th>
                                        <th>{{trans('reservation.nights')}}</th>
                                        <th>{{trans('reservation.guests.number')}} {{trans('reservation.guests.title')}}</th>
                                        <th>{{trans('reservation.guests.role')}}</th>
                                        <th>{{trans('reservation.guests.tax_night')}}</th>
                                        <th>{{trans('reservation.guests.total')}}</th>
                                        <th>{{trans('reservation.guests.nights')}}</th>
                                        <th>{{trans('bill.total_all')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($res->guests as $k => $guest)
                                    <tr>
                                        <td>{{$k+=1}}</td>
                                        <td>{{$guest->guest_started_at}}-{{$guest->guest_ended_at}}</td>
                                        <td>{{$guest->guest_night}}</td>
                                        <td>{{$guest->guest_number}}</td>
                                        <td>{{$rolesTrans[$guest->role_id]}}</td>
                                        <td>{{$guest->guest_tax}}</td>
                                        <td>{{$guest->guest_total}}</td>
                                        <td>{{$guest->guest_night * $guest->guest_number}}</td>
                                        <td>{{$guest->guest_all_total}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                @else
                                <b>Keine Gäste</b>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                    <thead>
                    <tr>
                        <td colspan="5"><h4>Du hast keine Reservierungen</h4></td>
                    </tr>
                    <tr>
                        <td colspan="5"><h5><a class="btn btn-default" href="{{URL::to('new_reservation')}}">Hier kannst Du reservieren</a></h5></td>
                    </tr>
                    </thead>
                @endif
            </table>
    </div>
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.no_delete_reservation')
@section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script>
        var startDate,
            endDate,
            rolesTaxes = {!! $roles !!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            datePickersStart = [],
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            res_lang = JSON.parse('{!!json_encode(trans('reservation'))!!}'),
            datePickersEnd = [],
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}');
    </script>
    <script src="{{asset('js/V3Reservation.min.js')}}"></script>
    <script src="{{asset('js/events.min.js')}}"></script>

@stop
@stop
