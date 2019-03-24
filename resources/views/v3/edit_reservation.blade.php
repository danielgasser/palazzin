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
    <div id="reservationInfo">
    </div>
        <form id="edit_reservation" method="post" action="{{  action('ReservationController@saveReservation', ['id' => $userRes[0]->id])  }}">
            {{ csrf_field() }}
            <input type="hidden" id="periodID" name="periodID" value="{{$userRes[0]->period_id}}">
            <input type="hidden" id="id" name="id" value="{{$userRes[0]->id}}">
            <input type="hidden" id="isEdit" name="isEdit" value="1">
            <div class="row" id="resButtons">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <button type="submit" title="{{trans('dialog.save')}}" class="btn btn-default show_reservation" disabled
                                id="save_reservation"><i class="fas fa-save"></i>{{trans('reservation.book')}}</button>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <button title="{{trans('dialog.delete')}}" class="btn btn-default show_reservation"
                                id="delete_reservation_{{$userRes[0]->id}}"><i class="fas fa-ban"></i>{{trans('reservation.delete')}}</button>
                    </div>
                </div>
            </div>
            <div class="row show_total_res arrow" id="show_res" style="display: block">
                <div class="hide-guest" id="hide_all_res">
                    <span id="hide_res" class="fas fa-caret-up"></span>&nbsp;{{trans('reservation.title_short')}}:
                    <div id="res_header_text">{{ $userRes[0]->reservation_title }}</div>
                    <input id="reservation_title" name="reservation_title" type="hidden" value="{{ $userRes[0]->reservation_title }}">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>{{trans('reservation.arrival_departure')}}</label>
                    <div class="input-daterange input-group">
                        <input type="text" id="reservation_started_at" name="reservation_started_at" class="input-sm form-control show_reservation{{ $errors->has('reservation_started_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.arrival')}}" readonly value="{{ $userRes[0]->reservation_started_at }}"/>
                        <span class="input-group-addon">bis</span>
                        <input type="text" id="reservation_ended_at" name="reservation_ended_at" class="input-sm form-control show_reservation{{ $errors->has('reservation_ended_at') ? ' input-error' : ''}}"
                               placeholder="{{trans('reservation.depart')}}" readonly value="{{ $userRes[0]->reservation_ended_at }}"/>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12" id="res_info">
                    <div class="form-group">
                        <div class="alert alert-info" id="total_res">
                            <span id="reservation_guest_num_total" data-toggle="tooltip" data-html="true">{{$userRes[0]->sum_guest}}</span> {{trans('reservation.guests.pe')}}&nbsp
                            CHF <span id="reservation_costs_total">{{$userRes[0]->sum_total}}</span>
                        </div>
                        <div id="addZeroGuest" style="display: none">
                            <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                    id="remove_guest_0"><i class="fas fa-trash-alt"></i></button>
                            <button title="{{trans('dialog.add_on_upper')}}"
                                    class="btn btn-danger btn-v3 show_reservation_guest" id="head_clone_guest_0"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                        <input type="hidden" name="hidden_reservation_costs_total" id="hidden_reservation_costs_total" value="{{$userRes[0]->sum_total_hidden}}">
                        <input type="hidden" name="hidden_reservation_guest_num_total" id="hidden_reservation_guest_num_total" value="{{$userRes[0]->sum_guest}}">
                    </div>
                </div>
            </div>
            <div id="guest_entries">
                @if(!is_null($userRes[0]->guests))
                    @if((sizeof($userRes[0]->guests) > 0))
                        @foreach($userRes[0]->guests as $i => $guest)
                            <div class="row" id="guests_date_{{ $i }}">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{{ $i }}">
                                    <span id="hide_guest_{{ $i }}" class="fas fa-caret-up"></span>&nbsp;<span id="guest_title_{{ $i }}">{!!trans('reservation.guest_many_no_js.one')!!}: {!! $guest->guest_title!!}</span>
                                    <input type="hidden" id="hidden_guest_title_{{ $i }}" name="hidden_guest_title[]" value="{!! $guest->guest_title!!}">
                                    <button title="{!!trans('dialog.delete')!!}" class="btn btn-danger btn-v3 show_reservation_guest"
                                            id="remove_guest_{{ $i }}"><i class="fas fa-trash-alt"></i></button>
                                    <button title="{{trans('dialog.add_on_upper')}}"
                                            class="btn btn-danger btn-v3 show_reservation_guest" id="clone_guest_{{ $i }}"><i
                                            class="fas fa-plus"></i></button>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" id="guest_id_{{ $i }}" name="guest_id[]" value="{!! $guest->id!!}">
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
                                               name="reservation_guest_num[]" data-toggle="tooltip" data-html="true" type="number" min="1"
                                               max="{{$settings['setting_num_bed'] - 1}}" value="{{ $guest->guest_number }}">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 no-hide">
                                    <div class="form-group">
                                        <div class="alert alert-info" id="total_guest_{{ $i }}">
                                            CHF <span id="reservation_guest_price_{{ $i }}">{{ $guest->guest_tax }}</span>/{!!trans('roles.tax_only')!!}&nbsp;
                                            <span id="number_nights_{{ $i }}">{{$guest->guest_night}}</span> {!!trans('reservation.nights')!!}
                                            CHF <span id="price_{{ $i }}">{{$guest->guest_all_total}}</span>
                                            <input type="hidden" name="number_nights[]" id="hidden_number_nights_{{ $i }}" value="{{$guest->guest_night}}">
                                            <input type="hidden" name="price[]" id="hidden_price_{{ $i }}" value="{{ $guest->guest_tax }}">
                                            <input type="hidden" name="hidden_reservation_guest_price[]" id="hidden_reservation_guest_price_{{ $i }}" value="{{$guest->guest_all_total}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
    @include('logged.dialog.delete_reservation')
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
            periodID = '{{$userRes[0]->period_id}}',
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
            endDateString,
            userPeriod = GlobalFunctions.getUserPeriod(periods, 'freeBeds'),
            reservations = JSON.parse('{!!$my_reservations!!}'),
            guestEntryView = '{!!  $guestEntryView !!}',
            newAllGuestBeds = GlobalFunctions.superFilter(reservationsPerPeriod, 'freeBeds_'),
            newUserRes = GlobalFunctions.superFilter(reservations, 'user_Res_Dates_'),
            allInputs = [];
    </script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <script src="{{asset('js/V3Reservation.min.js')}}"></script>
    <script>
        V3Reservation.writeLocalStorage(periods);
        V3Reservation.createTimeLine(periods);
    </script>
    <script src="{{asset('js/edit_reservation_init.min.js')}}"></script>
    <script src="{{asset('js/events.min.js')}}"></script>

@stop
@stop
