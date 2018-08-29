@extends('layout.master')

@section('content')
<a name="top"></a>
<div id="userInfo">
    <h1>{!!trans('reservation.title')!!}</h1>
    <h3>{!!trans('home.yourclan')!!}:
        @foreach($clan_name as $clan)
            <span class="{!! $clan->clan_code !!}-text">{!! $clan->clan_description !!}</span></li>
        @endforeach
    </h3>
</div>
<div id="upper">
    <h3 style="color: #dfb20d">WICHTIG! Bitte lesen!<br><a style="z-index: 1000;" target="_blank" href="{!!asset('assets/public/files/___checklist/Checkliste_Benutzer_Palazzin.pdf')!!}">Benutzer-Checkliste</a></h3>
    <div id="calendar"></div>
    <button class="btn btn-danger" id="reset_storage">Cache leeren</button>
</div>
@include('logged.dialog.guest_empty')
@include('logged.dialog.guest_nan')
@include('logged.dialog.night_nan')
@include('logged.dialog.no_delete_reservation')
@include('logged.dialog.cross_reserv')
@include('logged.dialog.cross_reserv_user_list')
@include('logged.dialog.no_free_beds')
@include('logged.dialog.delete_reservation')
@include('logged.dialog.not_invited')
   @section('scripts')
    @parent
    <script src="{!!asset('assets/js/DatePrototype.js')!!}"></script>
        <script src="{!!asset('assets/min/js/roomapp.min.js')!!}"></script>
        <script src="{!!asset('assets/js/Calendar.js')!!}"></script>
        <script src="{!!asset('assets/js/Reservation.js')!!}"></script>
        <script src="{!!asset('assets/js/inits/reservation_init.js')!!}"></script>
        <script src="{!!asset('assets/js/inits/reservation_edit_init.js')!!}"></script>
    <script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>
    <script>
            var locale = '{!!Lang::get('formats.langlangjs')!!}',
                lc = '{!!Lang::get('formats.langlang')!!}'.split('_')[0],
                df = '{!!Lang::get('formats.datepicker')!!}',
                dfs = '{!!Lang::get('formats.datepicker_sign')!!}',
                weekdayNames = {!!json_encode(Lang::get('calendar.weekdays'))!!},
                weekdayShortNames = {!!json_encode(Lang::get('calendar.weekdays-short'))!!},
                urlPeriods = '{!!URL::to('periods')!!}',
                userPeriods = {!!$periods!!},
                allReservations = {!!$allReservations!!},
                userRes = {!!json_encode($userRes)!!},
                periods = {!!json_encode($periodsAll)!!},
                roles = {!!$roles!!},
                rolesTrans = {!!json_encode($rolesTrans)!!},
                settings = {!!$settings!!},
                langStrings = {!!json_encode($langStrings)!!},
                langRes = {!!json_encode(Lang::get('reservation'))!!},
                langUser = {!!json_encode(Lang::get('userdata'))!!},
                langBill = {!!json_encode(Lang::get('bill'))!!},
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                timer = '',
                errors = {!!json_encode($errors)!!},
                todayIs ='{!!trans('navigation.today_is')!!}',
                baseUrl = '{!!URL::asset('')!!}',
                homeUrl = '{!!URL::to('/')!!}',
                guestLabel = {!!json_encode(Lang::get('reservation.guest_many'))!!},
                bedLabel = {!!json_encode(Lang::get('reservation.beds'))!!},
                only_free_beds = {!!json_encode(Lang::get('reservation.only_free_beds') . ' ' . Lang::get('reservation.beds')[1] . ' ' . $settings['setting_num_bed'] . ' ' . Lang::get('reservation.only_free_beds_for'))!!},
                withLabel = {!!json_encode(trans('dialog.with', ['m' => 'M']))!!},
                eventCounter = 0,
                userId = {!!json_encode($userId)!!},
                userRoles = {!!json_encode($userRoles)!!},
                userClan = {!!json_encode(Auth::user()->clan_id)!!},
                calDayId = '[id^="todaysDate_"]',
                ccd = parseInt(window.localStorage.getItem('currentCalendarDate'), 10),
                sd = (isNaN(ccd)) ? parseInt('{!!Session::get('currentCalendarDate')!!}', 10) * 1000 : ccd,
                startDate = (isNaN(sd)) ? new Date() : new Date(sd),
                duration = '{!! $settings['setting_calendar_duration'] !!}',
                endDate = new Date(),
                userlist = {!!json_encode($userlist)!!},
                not_invited = '{!!trans('reservation.warnings.not_invited', ['u' => 'user', 's' => 'start', 'e' => 'end'])!!}';
            endDate.setFullYear((startDate.getFullYear() + parseInt(duration, 10)));
            endDate.setDate(31);
            window.localStorage.setItem('currentCalendarDate', startDate.getTime());
            //window.Reservation.adaptInputs(false, false);
console.log(only_free_beds)

        </script>
    @if (!Request::is('/'))
        <script>
            jQuery(document).on('click', 'a, button', function () {
                $.ajax({
                    type: 'GET',
                    url: 'get-session',
                    success: function (data) {
                        if (data === '0') {
                            $('#login_again').modal({backdrop: 'static', keyboard: false});
                            setTimeout(function () {
                                $('#login_again_btn').trigger('click');
                            }, 1000)
                        }
                    }
                });

            });
            jQuery(document).on('click', '#login_again_btn', function () {
                window.location.href = '/logout';
            });
        </script>
    @endif

    <script src="{!!asset('assets/js/html.js')!!}"></script>
   @stop

@stop
