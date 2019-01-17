@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" href="{{asset('assets/css/v3/nehhkadam')}}-AnyPicker/anypicker-all.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{{asset('assets/js/v3/libs/nehhkadam')}}-AnyPicker/anypicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('assets/js/v3/libs/nehhkadam')}}-AnyPicker/anypicker-i18n.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css/datepicker.css')}}" rel="stylesheet" media="screen"
          type="text/css">


@stop

@section('content')
    <a name="top"></a>
    <div id="reservationInfo">
        <h4></h4>
    </div>
    <div id="upper">
        @forelse($userRes as $res)
            <form id="all_reservations_{{ $res->id }}" method="post" action="{{  route('edit_reservation', [$res->id])  }}">
            {{ csrf_field() }}
            <div class="row show_total_res arrow" id="show_res_{{ $res->id }}">
                <div class="hide-guest" id="hide_all_res_{{ $res->id }}">{{trans('reservation.title_short')}}: {{$res->reservation_title}}</div>
                <div class="col-md-1 col-sm-1 col-xs-12">
                        {{trans('reservation.arrival')}}<br>{{ $res->reservation_started_at }}
                </div>
                <div class="col-md-1 col-sm-1 col-xs-12">
                        {{trans('reservation.depart')}}<br>{{ $res->reservation_ended_at }}
                </div>
                <div class="col-md-1 col-sm-1 col-xs-12">
                    {{trans('reservation.guests.total_nights')}}<br>{{ $res->reservation_nights }}
                </div>
                <div class="col-md-1 col-sm-1 col-xs-12">
                    {{trans('bill.total_all_bill')}}<br><span id="reservation_costs_total_{{ $res->id }}">0.00</span>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-4">
                    {{trans('validation.attributes.reservation_guest_num')}}<br><span id="reservation_guest_num_total_{{ $res->id }}">0</span>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-4">
                        <button type="submit" title="{{trans('dialog.edit')}}" class="btn btn-danger btn-v3 show_reservation"
                                id="edit_reservation_{{ $res->id }}"><i class="fas fa-edit"></i></button>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-4">
                    <button title="{{trans('dialog.delete')}}" class="btn btn-danger btn-v3 show_reservation"
                                id="delete_reservation_{{ $res->id }}"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @foreach($res->guests as $guest)
            <div id="guest_entries_{{ $guest->id }}_{{ $res->id }}">
                <div class="row" id="guests_date_{{ $guest->id }}_{{ $res->id }}">
                    <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{{ $guest->id }}_{{ $res->id }}" style="cursor: initial">
                        <span id="guest_title_{{ $guest->id }}_{{ $res->id }}" style="font-size: 0.9em">{{trans('reservation.guest_many_no_js.one')}}: {!! $guest->guest_title!!}</span>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6">
                        {{trans('reservation.arrival')}}<br>{{ $guest->guest_started_at }}
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6">
                        {{trans('reservation.depart')}}<br>{{ $guest->guest_ended_at }}
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6">
                        {{trans('reservation.nights')}}<br>{{ $guest->guest_night }}
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-6">
                        {{trans('reservation.guest_kind')}}<br>{{ $rolesTrans[$guest->guest_tax_role_id] }}
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6">
                        {{trans('roles.role_tax')}}<br>{{ $guest->guest_tax }}
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-6">
                        {{trans('reservation.guests.number')}} {{trans('reservation.guests.title')}}<br>{{ $guest->guest_number }}
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-12">
                        {{trans('reservation.price')}}<br>{{ $guest->guest_tax }}
                    </div>
                </div>
            </div>
            @endforeach
        </form>
            @empty
            <script>
                $(document).ready(function () {
                    $('#noview').find('h1').html('{{ trans('reservation.no_bookings') }}')
                })
            </script>
        @endforelse
    </div>
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.deleted_reservation')
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
        document.addEventListener('scroll', function (event) {
            console.log('scrolling', event.target, event);
            if (event.target.id === 'idOfUl') { // or any other filtering condition
            }
        }, true);
    </script>
    <script>
        var startDate,
            endDate,
            rolesTaxes = {!! $roles !!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            datePickersStart = [],
            reservations = JSON.parse('{!!json_encode($userRes)!!}'),
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            datePickersEnd = [],
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}');
    </script>
    {{-- <script src="{{asset('assets/js/v3/global_functions/funcs.js')}}"></script>--}}
    <script src="{{asset('assets/js/v3/V3Reservation.js')}}"></script>
    <script>
       // $.holdReady(true);
        //V3Reservation.writeLocalStorage(periods);
        //V3Reservation.createTimeLine(periods);
    </script>
    <script src="{{asset('assets/js/v3/events.js')}}"></script>
    <script>
        $(document).ready(function () {
            let sum = 0,
            old_id;
            $.each($('[id^="reservation_guest_num_"]').not('[id^="reservation_guest_num_total_"]'), function (i, n) {
                let id = $(n).attr('id').split('_')[4];
                if (id !== old_id) {
                    sum = 0;
                }
                old_id = id;
                sum += parseInt($(n).val(), 10);
                $('#reservation_guest_num_total_' + id).html(sum);
            });
            old_id = 0;
            $.each($('[id^="number_nights_"]'), function (i, n) {
                let r_id = $(n).attr('id').split('_')[3],
                    g_id = $(n).attr('id').split('_')[2];
                if (g_id !== old_id) {
                    sum = 0;
                }
                old_id = g_id;
                if (parseFloat($('#reservation_guest_price_' + g_id + '_' + r_id).val()) > 0) {
                    sum += (parseInt($(n).val(), 10) * parseFloat($('#reservation_guest_price_' + g_id + '_' + r_id).val()) * parseInt($('#reservation_guest_num_' + g_id + '_' + r_id).val(), 10));
                }
                $('#price_' + g_id + '_' + r_id).val(sum.toFixed(2));
            });
            old_id = 0;
            $.each($('[id^="price_"]'), function (i, n) {
                let id = $(n).attr('id').split('_')[2];
                if (id !== old_id) {
                    sum = 0;
                }
                old_id = id;
                sum += parseFloat($(n).val());
                $('#reservation_costs_total_' + id).html(sum.toFixed(2));
            });
        })
    </script>

@stop
@stop
