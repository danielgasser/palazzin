@extends('layout.master')
@section('header')
    @parent

    <link rel="stylesheet" href="{{asset('assets/js/v3/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
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
        <form id="edit_reservation" method="post" action="{{  route('save_reservation')  }}">
            {{ csrf_field() }}
            <input type="hidden" id="periodID" name="periodID" value="">
            <input type="hidden" id="id" name="id" value="{{$userRes->id}}">
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
                    <div id="res_header_text"></div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>{{trans('reservation.arrival_departure')}}</label>
                    <div class="input-daterange input-group">
                        <input type="text" id="reservation_started_at" name="reservation_started_at" class="input-sm form-control show_reservation{{ $errors->has('reservation_started_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.arrival')}}" readonly value="{{ $userRes->reservation_started_at }}"/>
                        <span class="input-group-addon">bis</span>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" class="noClick input-sm form-control show_reservation{{ $errors->has('reservation_ended_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.depart')}}" readonly value="{{ $userRes->reservation_ended_at }}"/>
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
            var_dump(sizeof($userRes->guests))
            @endphp

            <div id="guest_entries">
                @if((sizeof($userRes->guests) > 0))
                @foreach($userRes->guests as $i => $guest)
                        <div class="row" id="guests_date_{{ $i }}">
                            <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{{ $i }}">
                                <span id="hide_guest_{{ $i }}" class="fas fa-caret-up"></span>&nbsp;<span id="guest_title_{{ $i }}">{!!trans('reservation.guest_many_no_js.one')!!}: </span>
                                <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                        id="remove_guest_{{ $i }}"><i class="fas fa-trash-alt"></i></button>

                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label>{!!trans('reservation.arrival_departure')!!}</label>
                                <div class="input-daterange input-group" id="guestDates_{{ $i }}">
                                    <input type="text" id="reservation_guest_started_at_{{ $i }}" name="reservation_guest_started_at[]" class="input-sm form-control show_reservation{{ $errors->has('reservation_guest_started_at.' . $i) ? ' input-error' : ''}}"
                                           placeholder="{!!trans('reservation.arrival')!!}" readonly value="{{ $guest->guest_started_at }}"/>
                                    <span class="input-group-addon">bis</span>
                                    <input type="text" id="reservation_guest_ended_at_{{ $i }}" name="reservation_guest_ended_at[]" class="input-sm form-control show_reservation{{ $errors->has('reservation_guest_ended_at.' . $i) ? ' input-error' : ''}}"
                                           placeholder="{!!trans('reservation.depart')!!}" readonly value="{{ $guest->guest_ended_at }}"/>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>{!!trans('reservation.guest_kind')!!}</label>
                                    <select class="form-control show_reservation_guest{{ $errors->has('reservation_guest_guests.' . $i) ? ' input-error' : ''}}" id="reservation_guest_guests_{{ $i }}"
                                            name="reservation_guest_guests[]">
                                        @foreach($rolesTrans as $k => $r)
                                            <option {{ ($guest->role_id == $k) ? ' selected' : '' }} value="{{$k}}">{{$r}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <label>{!!trans('reservation.guests.number')!!} {!!trans('reservation.guests.title')!!}</label>
                                    <input class="form-control show_reservation_guest{{ $errors->has('reservation_guest_num.' . $i) ? ' input-error' : ''}}" id="reservation_guest_num_{{ $i }}"
                                           name="reservation_guest_num[]" data-toggle="tooltip" data-html="true" title="{!!trans('dialog.texts.warning_no_free_beds')!!}" type="number" min="1"
                                           max="{{$settings['setting_num_bed'] - 1}}" value="{{ $guest->guest_number }}">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 no-hide">
                                <div class="form-group">
                                    <div class="alert alert-info" id="total_guest_{{ $i }}">
                                        CHF <span id="reservation_guest_price_{{ $i }}">0</span>/{!!trans('roles.tax_only')!!}&nbsp;
                                        <span id="number_nights_{{ $i }}">0</span> {!!trans('reservation.nights')!!}
                                        CHF <span id="price_{{ $i }}">0.-</span>
                                        <input type="hidden" name="number_nights[]" id="hidden_number_nights_{{ $i }}" value="{{ old('number_nights.' . $i) }}">
                                        <input type="hidden" name="price[]" id="hidden_price_{{ $i }}" value="{{ old('price.' . $i) }}">
                                        <input type="hidden" name="hidden_reservation_guest_price[]" id="hidden_reservation_guest_price_{{ $i }}" value="{{ old('reservation_guest_price.' . $i) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                @endforeach
                    @endif
            </div>
        </form>
    </div>
    {{--
                      @include('logged.dialog.guest', ['i' => $i, 'one_guest' => $guest])
  @include('logged.dialog.reservation_exists')
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
            resEndPicker,
            startGuestPicker = [],
            endGuestPicker = [],
            //guestEntryView = '{{--  $guestEntryView --}}',
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
            localStorage.setItem('new_res', '0');
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
