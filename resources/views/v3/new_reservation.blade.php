@extends('layout.master')
@section('header')
    @parent

    <link rel="stylesheet" href="{{asset('assets/js/v3/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <script type="text/javascript"
            src="{{asset('assets/js/v3')}}/datepicker-init.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css')}}/bootstrap-datepicker.css"
          rel="stylesheet" media="screen" type="text/css">

@stop

@section('content')

    <a name="top"></a>
    <div id="reservationInfo">
    </div>
        <form id="new_reservation" method="post" action="{{  route('save_reservation')  }}">
            {{ csrf_field() }}
            <input type="hidden" id="periodID" name="periodID" value="">
            <div class="row">
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" title="{{trans('dialog.save')}}" class="btn btn-danger btn-v3 show_reservation" disabled
                                id="save_reservation"><i class="fas fa-save"></i></button>
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button title="{{trans('dialog.add_on_upper')}}"
                                class="btn btn-danger btn-v3 show_reservation_guest" id="clone_guest" disabled><i
                                class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button title="{{trans('dialog.delete')}}" class="btn btn-danger btn-v3 show_reservation" disabled
                                id="reset_reservation"><i class="fas fa-ban"></i></button>
                    </div>
                </div>

            </div>
            <div class="row show_total_res arrow" id="show_res" style="display: block">
                <div class="hide-guest" id="hide_all_res">
                    <span id="hide_res" class="fas fa-caret-up"></span>&nbsp;{{trans('reservation.title_short')}}:
                    <div id="res_header_text">
                    </div>
                    <input type="hidden" id="reservation_title" name="reservation_title" value="{{ old('reservation_title') }}"/>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>{{trans('reservation.arrival_departure')}}</label>
                    <div class="input-daterange input-group">
                        <input type="text" id="reservation_started_at" name="reservation_started_at" class="input-sm form-control show_reservation{{ $errors->has('reservation_started_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.arrival')}}" readonly value="{{ old('reservation_started_at') }}"/>
                        <span class="input-group-addon">bis</span>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" class="noClick input-sm form-control show_reservation{{ $errors->has('reservation_ended_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.depart')}}" readonly value="{{ old('reservation_ended_at') }}"/>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12" id="res_info">
                    <div class="form-group">
                        <div class="alert alert-info" id="total_res">
                            <span id="reservation_guest_num_total" data-toggle="tooltip" data-html="true" title="{{trans('dialog.texts.warning_no_free_beds')}}">1</span> {{trans('reservation.guests.pe')}}&nbsp;
                            CHF <span id="reservation_costs_total">0.-</span>
                        </div>
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total" value="{{ old('hidden_reservation_costs_total') }}">
                        <input type="hidden" name="hidden_reservation_guest_num_total" id="hidden_reservation_guest_num_total" value="{{ old('hidden_reservation_guest_num_total') }}">
                    </div>
                </div>
            </div>
            @php
            $c = (old('reservation_guest_started_at') == null) ? 1 : count(old('reservation_guest_started_at'));
            @endphp

            <div id="guest_entries">
                @for($i = 0; $i < $c; $i++)
                    @include('logged.dialog.guest_entry', ['rolesTrans' => $rolesTrans])
                @endfor
            </div>
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
    @if ($errors->any())
    @include('logged.dialog.no_free_beds')
    @endif
    @include('logged.dialog.over_period')
    @include('logged.dialog.delete_guest')
    @include('logged.dialog.free_beds')
@section('scripts')
    @parent
    <script>
        var guestsDates = $('[id^="guests_date"]'),
            startDate,
            rolesTaxes = {!! $roleTaxes !!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            monthNames = JSON.parse('{!!json_encode(trans('calendar.month-names-short'))!!}'),
            datePickersStart = [],
            periods = JSON.parse('{!!json_encode($periods)!!}'),
            datePickerPeriods = JSON.parse('{!!json_encode($periodsDatePicker)!!}'),
            periodID = periods[0].id,
            endDate,
            reservationsPerPeriod = JSON.parse('{!! $reservationsPerPeriod !!}'),
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            datePickersEnd = [],
            today = new Date(),
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}'),
            afterValidation = '{{ ($errors->any()) }}',
            uID = JSON.parse('{!!json_encode($userClan[0]) !!}'),
            resStartPicker,
            guestEntryView = '{!!  $guestEntryView !!}',
            resEndPicker,
            startGuestPicker = [],
            endGuestPicker = [],
            newAllGuestBeds = [];
            for (let i = 0; i < reservationsPerPeriod.length; i++) {
                Object.keys(reservationsPerPeriod[i]).filter(function(k) {
                    if (k.indexOf('freeBeds') === 0 && reservationsPerPeriod[i][k] !== undefined) {
                        newAllGuestBeds[k] = reservationsPerPeriod[i][k];
                    }
                });
            }
            console.log(reservationsPerPeriod, newAllGuestBeds)
    </script>
    <script>
        $(document).ready(function () {
            localStorage.clear();
            localStorage.setItem('new_res', '1');
            if (afterValidation ==='1') {
                localStorage.setItem('new_res', '0');
                let startDateString = '{{ old('reservation_started_at') }}'.split('.');
                startDate = new Date(startDateString[2], (startDateString[1] - 1), startDateString[0], 0, 0, 0);
                V3Reservation.init('{{ old('periodID') }}', true, startDate);
            } else {
                V3Reservation.init(periodID, true, new Date());
            }
        })
    </script>
    <script src="{{asset('assets/js/v3/V3Reservation.js')}}"></script>
    <script>
        V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{{asset('assets/js/v3/events.js')}}"></script>
@stop
@stop
