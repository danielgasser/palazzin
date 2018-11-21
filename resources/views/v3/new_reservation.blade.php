@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" href="{!!asset('assets/js/v3/bootstrap-datepicker')!!}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/bootstrap-datepicker')!!}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{!!asset('assets/js/v3/bootstrap-datepicker')!!}/locales/bootstrap-datepicker.de.min.js"></script>
    <script type="text/javascript"
            src="{!!asset('assets/js/v3')!!}/datepicker-init.js"></script>
    <link rel="stylesheet" href="{!!asset('assets/css')!!}/bootstrap-datepicker.css"
          rel="stylesheet" media="screen" type="text/css">
@stop

@section('content')

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
                <div class="hide-guest" id="hide_all_res">
                    <span id="hide_res" class="fas fa-caret-up"></span>&nbsp;{!!trans('reservation.title_short')!!}
                    <div id="res_header_text"></div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <label>{!!trans('reservation.arrival_departure')!!}</label>
                    <div class="input-daterange input-group">
                        <input type="text" id="reservation_started_at" name="reservation_started_at" class="input-sm form-control show_reservation{{ $errors->has('reservation_started_at') ? ' input-error' : ''}}"
                               placeholder="{!!trans('reservation.arrival')!!}" readonly value="{!! old('reservation_started_at') !!}"/>
                        <span class="input-group-addon">bis</span>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" class="noClick input-sm form-control show_reservation{{ $errors->has('reservation_ended_at') ? ' input-error' : ''}}"
                               placeholder="{!!trans('reservation.depart')!!}" readonly value="{!! old('reservation_ended_at') !!}"/>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('reservation.guests.total_nights')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_nights_total"></div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>{!!trans('bill.total_all_bill')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_costs_total">0.00</div>
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total" value="{!! old('hidden_reservation_costs_total') !!}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>{!!trans('reservation.total_pers')!!}</label>
                        <div class="form-control v3-disabled show_reservation" id="reservation_guest_num_total" data-toggle="tooltip" data-html="true" title="{!!trans('dialog.texts.warning_no_free_beds')!!}">0</div>
                        <input type="hidden" name="hidden_reservation_guest_num_total" data-toggle="tooltip" data-html="true" title="{!!trans('dialog.texts.warning_no_free_beds')!!}" id="hidden_reservation_guest_num_total" value="{!! old('hidden_reservation_guest_num_total') !!}">
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
                        <button title="{!!trans('dialog.add_on_upper')!!}"
                                class="btn btn-danger btn-v3 show_reservation_guest" id="clone_guest" disabled><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation"
                                id="reset_reservation"><i class="fas fa-ban"></i></button>
                    </div>
                </div>
            </div>
            @php
            $c = (old('reservation_guest_started_at') == null) ? 1 : count(old('reservation_guest_started_at'));
            @endphp

            @for($i = 0; $i < $c; $i++)
            <div id="guest_entries">
                @include('logged.dialog.guest')
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
    @include('logged.dialog.no_free_beds')
    --}}
    @include('logged.dialog.delete_guest')
    @include('logged.dialog.free_beds')
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
            rolesTaxes = {!!$roleTaxes!!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            monthNames = JSON.parse('{!!json_encode(trans('calendar.month-names-short'))!!}'),
            datePickersStart = [],
            periods = JSON.parse('{!!json_encode($periods)!!}'),
            datePickerPeriods = JSON.parse('{!!json_encode($periodsDatePicker)!!}'),
            periodID = periods[0].id,
            endDate,
            reservationsPerPeriod = JSON.parse('{!!$reservationsPerPeriod!!}'),
            guestTitle = '{!!trans('reservation.guest_many_no_js.one')!!}: ',
            datePickersEnd = [],
            today = new Date(),
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}'),
            afterValidation = '{!! ($errors->any()) !!}',
            resStartPicker,
            resEndPicker,
            startGuestPicker = [],
            endGuestPicker = [];
            console.log(datePickerPeriods)
    </script>
    <script src="{!!asset('assets/js/v3/global_functions/funcs.js')!!}"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'manual'
            });
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
        V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{!!asset('assets/js/v3/events.js')!!}"></script>
@stop
@stop
