@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" href="{!!asset('assets/css/v3/nehhkadam')!!}-AnyPicker/anypicker-all.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/libs/nehhkadam')!!}-AnyPicker/anypicker.js"></script>
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/libs/nehhkadam')!!}-AnyPicker/anypicker-i18n.js"></script>
    <link rel="stylesheet" href="{!!asset('assets/css/datepicker.css')!!}" rel="stylesheet" media="screen"
          type="text/css">

@stop

@section('content')
    @include('logged.dialog.free_beds')
    @include('logged.dialog.timeliner')
    <a name="top"></a>
    <div id="reservationInfo">
        <h1>{!!trans('navigation.new_reservation')!!}</h1>
    </div>
    <div id="upper">
    </div>
        <form id="new_reservation" method="post" action="{!!  route('save_reservation')  !!}">
            {!! csrf_field() !!}
            <input type="hidden" id="periodID" name="periodID" value="">
            <div class="row show_total_res arrow" id="show_res" style="display: none">
                <div class="hide-guest" id="hide_all_res"><span id="hide_res" class="fas fa-caret-up"></span>&nbsp;{!!trans('reservation.title_short')!!}</div>
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.arrival')!!}</label>
                        <input type="text" id="reservation_started_at" name="reservation_started_at" data-field="date"
                               data-startend="start" data-startendelem="#reservation_ended_at"
                               data-label="{!!trans('reservation.arrival')!!}" class="form-control show_reservation{{ $errors->has('reservation_started_at') ? ' input-error' : ''}}"
                               placeholder="TT.MM.JJJJ" readonly value="{!! old('reservation_started_at') !!}"/>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.depart')!!}</label>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" data-field="date"
                               data-startend="end" data-startendelem="#reservation_started_at"
                               data-label="{!!trans('reservation.depart')!!}" class="form-control show_reservation{{ $errors->has('reservation_ended_at') ? ' input-error' : ''}}"
                               placeholder="TT.MM.JJJJ" readonly value="{!! old('reservation_started_at') !!}"/>
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
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total" value="{!! old('hidden_reservation_costs_total') !!}">
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>{!!trans('validation.attributes.reservation_guest_num')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_guest_num_total">0</div>
                        <input type="hidden" name="hidden_reservation_guest_num_total" id="hidden_reservation_guest_num_total" value="{!! old('hidden_reservation_guest_num_total') !!}">
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
            @php
            $c = (old('reservation_guest_started_at') == null) ? 1 : count(old('reservation_guest_started_at'));
            @endphp

            @for($i = 0; $i < $c; $i++)
            <div id="guest_entries">
                <div class="row" id="guests_date_{!! $i !!}" style="display: none">
                    <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{!! $i !!}"><span id="hide_guest_{!! $i !!}" class="fas fa-caret-up"></span>&nbsp;<span id="guest_title_{!! $i !!}">{!!trans('reservation.guest_many_no_js.one')!!}: </span>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.arrival')!!}</label>
                            <input type="text" id="reservation_guest_started_at_{!! $i !!}" name="reservation_guest_started_at[]"
                                   data-field="date" data-label="{!!trans('reservation.arrival')!!}"
                                   class="form-control show_reservation_guest{{ $errors->has('reservation_guest_started_at.' . $i) ? ' input-error' : ''}}" placeholder="TT.MM.JJJJ" readonly value="{!! old('reservation_guest_started_at.' . $i) !!}"/>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.depart')!!}</label>
                            <input type="text" id="reservation_guest_ended_at_{!! $i !!}" name="reservation_guest_ended_at[]"
                                   data-field="date" data-label="{!!trans('reservation.depart')!!}"
                                   class="form-control show_reservation_guest{{ $errors->has('reservation_guest_ended_at') ? ' input-error' : ''}}" placeholder="TT.MM.JJJJ" readonly value="{!! old('reservation_guest_ended_at.' . $i) !!}"/>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.nights')!!}</label>
                            <div class="form-control v3-disabled show_reservation_guest" id="number_nights_{!! $i !!}"></div>
                            <input type="hidden" name="number_nights[]" id="hidden_number_nights_{!! $i !!}" value="{!! old('number_nights.' . $i) !!}">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>{!!trans('reservation.guest_kind')!!}</label>
                            <select class="form-control show_reservation_guest{{ $errors->has('reservation_guest_guests') ? ' input-error' : ''}}" id="reservation_guest_guests_{!! $i !!}"
                                    name="reservation_guest_guests[]">
                                @foreach($rolesTrans as $k => $r)
                                    <option {!! (old('reservation_guest_guests.' . $i) == $k) ? ' selected' : '' !!} value="{!!$k!!}">{!!$r!!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('roles.role_tax')!!}</label>
                            <input class="form-control v3-disabled show_reservation_guest" type="number" name="reservation_guest_price[]" id="reservation_guest_price_{!! $i !!}" value="{!! old('reservation_guest_price.' . $i) !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('reservation.guests.number')!!} {!!trans('reservation.guests.title')!!}</label>
                            <input class="form-control show_reservation_guest{{ $errors->has('reservation_guest_num') ? ' input-error' : ''}}" id="reservation_guest_num_{!! $i !!}"
                                   name="reservation_guest_num[]" type="number" min="1"
                                   max="{!!$settings['setting_num_bed'] - 1!!}" value="{!! old('reservation_guest_num.' . $i) !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>{!!trans('reservation.price')!!}</label>
                            <div class="form-control v3-disabled show_reservation_guest" id="price_{!! $i !!}"></div>
                            <input type="hidden" name="price[]" id="hidden_price_{!! $i !!}" value="{!! old('price.' . $i) !!}">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button title="{!!trans('dialog.add_on_upper')!!}"
                                    class="btn btn-danger btn-v3 show_reservation_guest" id="clone_guest_{!! $i !!}" disabled><i
                                        class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-3 col-xs-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                    id="remove_guest_{!! $i !!}"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
                @endfor
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
            monthNames = JSON.parse('{!!json_encode(trans('calendar.month-names-short'))!!}'),
            datePickersStart = [],
            periods = JSON.parse('{!!json_encode($periods)!!}'),
            periodID = periods[0].id,
            guestTitle = '{!!trans('reservation.guest_many_no_js.one')!!}: ',
            datePickersEnd = [],
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}'),
            afterValidation = '{!! ($errors->any()) !!}';
    </script>
    <script src="{!!asset('assets/js/v3/global_functions/funcs.js')!!}"></script>
    <script>
        $(document).ready(function () {
            localStorage.setItem('new_res', '1');
            if (afterValidation ==='1') {
                localStorage.setItem('new_res', '0');
                let startDateString = '{!! old('reservation_started_at') !!}'.split('.');
                startDate = new Date(startDateString[2], (startDateString[1] - 1), startDateString[0], 0, 0, 0);
                V3Reservation.init('{!! old('periodID') !!}', true, startDate);
            } else {
                V3Reservation.init(periodID, true, new Date());
            }
        })
    </script>
    <script src="{!!asset('assets/js/v3/V3Reservation.js')!!}"></script>
    <script>
        //V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{!!asset('assets/js/v3/events.js')!!}"></script>
@stop
@stop
