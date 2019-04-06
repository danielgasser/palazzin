@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" href="{{asset('css/reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" href="{{asset('css/all_reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" href="{{asset('css/new_reservation.min.css')}}"
          rel="stylesheet" media="screen" type="text/css">
    <link rel="stylesheet" href="{{asset('libs/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">

    <link rel="stylesheet" href="{{asset('css')}}/bootstrap-datepicker.min.css"
          rel="stylesheet" media="screen" type="text/css">

@stop

@section('content')

    <a name="top"></a>
    </div>
        <form id="new_reservation" method="post" action="{{  route('save_reservation')  }}">
            {{ csrf_field() }}
            <input type="hidden" id="periodID" name="periodID" value="">
            <div class="row" id="resButtons">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <button type="submit" title="{{trans('dialog.save')}}" class="btn btn-default show_reservation" disabled
                                id="save_reservation"><i class="fas fa-save"></i>{{trans('reservation.book')}}</button>
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
                            <span id="reservation_guest_num_total" data-toggle="tooltip" data-html="true">1</span> {{trans('reservation.guests.pe')}}&nbsp;
                            CHF <span id="reservation_costs_total">0.-</span>
                        </div>
                        <div id="addZeroGuest" style="display: none">
                            <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                    id="remove_guest_0"><i class="fas fa-trash-alt"></i></button>
                            <button title="{{trans('dialog.add_on_upper')}}"
                                    class="btn btn-danger btn-v3 show_reservation_guest" id="head_clone_guest_0"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total" value="{{ old('hidden_reservation_costs_total') }}">
                        <input type="hidden" name="hidden_reservation_guest_num_total" id="hidden_reservation_guest_num_total" value="{{ old('hidden_reservation_guest_num_total') or 1 }}">
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
    @if ($errors->any())
    @include('logged.dialog.no_free_beds')
    @endif
    @include('logged.dialog.over_period')
    @include('logged.dialog.delete_guest')
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
            reservationsSums = JSON.parse('{!! json_encode($reservationsSum) !!}'),
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
            allClans = JSON.parse('{!!json_encode($allClans) !!}'),
            startGuestPicker = [],
            endGuestPicker = [],
            oldReservationStarted = '{{ old('reservation_started_at') }}',
            oldPeriodID = '{{ old('periodID') }}',
            reservations = JSON.parse('{!!$userRes!!}'),
            newAllGuestBeds = GlobalFunctions.superFilter(reservationsSums, 'freeBeds_'),
            newUserRes = GlobalFunctions.superFilter(reservations, 'user_Res_Dates_'),
            allInputs = [];
            console.log(newAllGuestBeds, newUserRes)
    </script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <script src="{{asset('js/V3Reservation.min.js')}}"></script>
    <script src="{{asset('js/new_reservation_init.min.js')}}"></script>
    <script>
        V3Reservation.writeFreeBedsStorage(newAllGuestBeds, 'freeBeds_', today, today);
        V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{{asset('js/events.min.js')}}"></script>
@stop
@stop
