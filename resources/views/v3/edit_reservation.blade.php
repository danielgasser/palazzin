@extends('layout.master')
@section('header')
    @parent
    {{--
    <link rel="stylesheet" href="{!!asset('assets/css/v3/nehhkadam')!!}-AnyPicker/anypicker-all.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/libs/nehhkadam')!!}-AnyPicker/anypicker.min.js"></script>
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/libs/nehhkadam')!!}-AnyPicker/anypicker-i18n.js"></script>
    <link rel="stylesheet" href="{!!asset('assets/css/datepicker.css')!!}" rel="stylesheet" media="screen"
          type="text/css">
--}}
    <link rel="stylesheet" href="{!!asset('assets/js/v3/bootstrap-datepicker')!!}css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/bootstrap-datepicker')!!}js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/bootstrap-datepicker')!!}locales/bootstrap-datepicker.de.min.js"></script>
@stop

@section('content')
    @include('logged.dialog.free_beds')
    <a name="top"></a>
    <div id="reservationInfo">
        <h1>{!!trans('navigation.edit_reservation')!!}</h1>
        <h4></h4>
    </div>
    <div id="upper">
        <form id="new_reservation" method="post" action="{!!  route('save_reservation', $userRes->id)  !!}">
            {!! csrf_field() !!}
            <div class="row show_total_res arrow" id="show_res">
                <div class="hide-guest" id="hide_all_res"><span id="hide_res" class="fas fa-caret-up"></span>&nbsp;{!!trans('reservation.title_short')!!}</div>
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.arrival')!!}</label>
                        <input type="text" id="reservation_started_at" name="reservation_started_at" data-field="date"
                               data-startend="start" data-startendelem="#reservation_ended_at"
                               data-label="{!!trans('reservation.arrival')!!}" class="form-control show_reservation"
                               placeholder="TT.MM.JJJJ" readonly value="{!! $userRes->reservation_started_at !!}"/>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.depart')!!}</label>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" data-field="date"
                               data-startend="end" data-startendelem="#reservation_started_at"
                               data-label="{!!trans('reservation.depart')!!}" class="form-control show_reservation"
                               placeholder="TT.MM.JJJJ" readonly value="{!! $userRes->reservation_ended_at !!}"/>
                    </div>
                </div>
                <div class="col-md-1 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.guests.total_nights')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_nights_total"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('bill.total_all_bill')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_costs_total">0.00</div>
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total">
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>{!!trans('validation.attributes.reservation_guest_num')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_guest_num_total">0</div>
                        <input type="hidden" name="hidden_reservation_guest_num_total" id="hidden_reservation_guest_num_total">
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" title="{!!trans('dialog.save')!!}" class="btn btn-danger btn-v3 show_reservation"
                                id="save_reservation"><i class="fas fa-save"></i></button>
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button title="{!!trans('dialog.no')!!}" class="btn btn-danger btn-v3 show_reservation"
                                id="reset_reservation"><i class="fas fa-ban"></i></button>
                    </div>
                </div>
            </div>
            @foreach($userRes->guests as $guest)
            <div id="guest_entries">
                <div class="row" id="guests_date_{!! $guest->id !!}_{!! $userRes->id !!}">
                    <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{!! $guest->id !!}_{!! $userRes->id !!}"><span id="hide_guest_{!! $guest->id !!}_{!! $userRes->id !!}" class="fas fa-caret-up"></span>&nbsp;<span id="guest_title_{!! $guest->id !!}_{!! $userRes->id !!}">{!!trans('reservation.guest_many_no_js.one')!!}: </span>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.arrival')!!}</label>
                            <input type="text" id="reservation_guest_started_at_{!! $guest->id !!}_{!! $userRes->id !!}" name="reservation_guest_started_at_{!! $guest->id !!}_{!! $userRes->id !!}"
                                   data-field="date" data-label="{!!trans('reservation.arrival')!!}"
                                   class="form-control show_reservation_guest" placeholder="TT.MM.JJJJ" readonly value="{!! $guest->guest_started_at !!}"/>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.depart')!!}</label>
                            <input type="text" id="reservation_guest_ended_at_{!! $guest->id !!}_{!! $userRes->id !!}" name="reservation_guest_ended_at_{!! $guest->id !!}_{!! $userRes->id !!}"
                                   data-field="date" data-label="{!!trans('reservation.depart')!!}"
                                   class="form-control show_reservation_guest" placeholder="TT.MM.JJJJ" readonly value="{!! $guest->guest_ended_at !!}"/>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label for="number_nights_{!! $guest->id !!}_{!! $userRes->id !!}">{!!trans('reservation.nights')!!}</label>
                            <input class="form-control v3-disabled show_reservation_guest" type="number" name="number_nights_{!! $guest->id !!}_{!! $userRes->id !!}" id="number_nights_{!! $guest->id !!}_{!! $userRes->id !!}" value="{!! $guest->guest_night !!}">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.guest_kind')!!}</label>
                            <select class="form-control show_reservation_guest" id="reservation_guest_guests_{!! $guest->id !!}_{!! $userRes->id !!}"
                                    name="reservation_guest_guests_{!! $guest->id !!}_{!! $userRes->id !!}">
                                @foreach($rolesTrans as $k => $r)
                                    <option {!! ($guest->role_id === $k) ? 'selected' : '' !!} value="{!!$k!!}">{!!$r!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('roles.role_tax')!!}</label>
                            <input class="form-control v3-disabled show_reservation_guest" type="number" name="reservation_guest_price_{!! $guest->id !!}_{!! $userRes->id !!}" id="reservation_guest_price_{!! $guest->id !!}_{!! $userRes->id !!}" value="{!! $guest->guest_tax !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('reservation.guests.number')!!} {!!trans('reservation.guests.title')!!}</label>
                            <input class="form-control show_reservation_guest" id="reservation_guest_num_{!! $guest->id !!}_{!! $userRes->id !!}"
                                   name="reservation_guest_num[]" type="number" min="1"
                                   max="{!!$settings['setting_num_bed'] - 1!!}" value="{!! $guest->guest_number !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('reservation.price')!!}</label>
                            <div class="form-control v3-disabled show_reservation_guest" id="price_{!! $guest->id !!}_{!! $userRes->id !!}"></div>
                            <input type="hidden" name="price[]" id="hidden_price_{!! $guest->id !!}_{!! $userRes->id !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button title="{!!trans('dialog.add_on_upper')!!}"
                                    class="btn btn-danger btn-v3 show_reservation_guest" id="clone_guest_{!! $guest->id !!}_{!! $userRes->id !!}" disabled><i
                                        class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                    id="remove_guest_{!! $guest->id !!}_{!! $userRes->id !!}"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
                @endforeach
        </form>
    </div>
    @include('logged.dialog.reservation_exists')
    {{--
    @include('logged.dialog.guest_nan')
    @include('logged.dialog.night_nan')
    @include('logged.dialog.no_delete_reservation')
    @include('logged.dialog.cross_reserv')
    @include('logged.dialog.cross_reserv_user_list')
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.not_invited')
    --}}
    @include('logged.dialog.no_free_beds')
    @include('logged.dialog.no_guest_only_you')

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
        var guestsDates = $('[id^="guests_date"]'),
            startDate,
            endDate,
            rolesTaxes = {!!$roles!!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            datePickersStart = [],
            periods = JSON.parse('{!!json_encode($periods)!!}'),
            guestTitle = '{!!trans('reservation.guest_many_no_js.one')!!}: ',
            datePickersEnd = [],
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}');
    </script>
    <script src="{!!asset('assets/js/v3/global_functions/funcs.js')!!}"></script>
    <script>
        $(document).ready(function () {
            var inputStart,
                inputEnd,
                id = $(this).attr('id'),
                dateStringStart = '{!! $userRes->reservation_started_at !!}'.split('.'),
                dateStringEnd = '{!! $userRes->reservation_ended_at !!}'.split('.'),
                period,
                today = new Date();
            today.setHours(0, 0, 0, 0);
            V3Reservation.periodID = '{!! $userRes->period_id !!}';
            period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID));
            startDate = new Date(dateStringStart[2], (dateStringStart[1] - 1), dateStringStart[0], 0, 0, 0);
            endDate = new Date(dateStringEnd[2], (dateStringEnd[1] - 1), dateStringEnd[0], 0, 0, 0);
            startDate.setHours(0, 0, 0, 0);
            endDate.setHours(0, 0, 0, 0);
            V3Reservation.getFreeBeds(startDate, endDate, true, 'reservation/get-per-period', 'occupiedBeds_');
            $('#reservationInfo>h4').html(reservationStrings.prior + ': ' + '<span class="' + period.clan_code + '-text">' + period.clan_description + '</span>');
            //V3Reservation.createIOSDatePicker(['#reservation_started_at', '#reservation_ended_at', '#reservation_nights_total'], startDate, endDate, V3Reservation.periodID);
            //V3Reservation.createIOSDatePicker(['#reservation_guest_started_at_{!! $guest->id !!}_{!! $userRes->id !!}', '#reservation_guest_ended_at_{!! $guest->id !!}_{!! $userRes->id !!}', '#number_nights_{!! $guest->id !!}_{!! $userRes->id !!}'], startDate, endDate, V3Reservation.periodID);
        })
    </script>
    <script>
        $(document).ready(function () {
            localStorage.setItem('new_res', '0');
        })
    </script>
    <script src="{!!asset('assets/js/v3/V3Reservation.js')!!}"></script>
    <script>
        V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{!!asset('assets/js/v3/events.js')!!}"></script>
@stop
@stop
