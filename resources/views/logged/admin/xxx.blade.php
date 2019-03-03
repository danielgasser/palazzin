@extends('layout.master')
@section('content')
<div>
    <h3></h3>
</div>
<div id="loginListTable">
    <h3>Logins {{ trans('reservation.from') }} <span id="titleStart">{{ $todayMonthYearStart }}</span> <span id="between">{{ trans('reservation.till') }}</span> <span id="titleEnd">{{ $todayMonthYearEnd }}</span></h3>
    <div id="chooseLogins">
        {{ Form::open(array('id' => 'searchLogins')) }}
        <fieldset data-role="controlgroup" data-type="horizontal">
            <legend>{{ Lang::get('reservation.from2') }}</legend>
            {{ Form::label('startMonthChoice', Lang::get('reservation.month')) }}
            {{ Form::select('startMonthChoice', $monthNames, $startMonthSelected) }}
            {{ Form::label('startYearChoice', Lang::get('reservation.year')) }}
            {{ Form::select('startYearChoice', $years, $startYearSelected) }}
        </fieldset>
        <fieldset data-role="controlgroup" data-type="horizontal">
            <legend>{{ Lang::get('reservation.till') }}</legend>
            {{ Form::label('endMonthChoice', Lang::get('reservation.month')) }}
            {{ Form::select('endMonthChoice', $monthNames, $endMonthSelected) }}
            {{ Form::label('endYearChoice', Lang::get('reservation.year')) }}
            {{ Form::select('endYearChoice', $years, $endYearSelected) }}
        </fieldset>
        {{ Form::close() }}
    </div>
    <div class="head-list">
        <div style="font-weight: bold">
            <div class="mini-left">Total</div>
            <div class="short-left">{{ Lang::get('userdata.user_login_name') }}</div>
            <div class="midi-left">{{ Lang::get('reservation.date') }}</div>
            <div class="long-right">Timeline</div>
        </div>
        <div class="break-it" style="border-bottom: none"></div>
        <div id="loginDataContent">
        @if(sizeof($loginStats) > 0)
        @foreach($loginStats as $ls)
     {{-- ToDo --}}
                          {{--print_r(array_map("unserialize", array_unique(array_map("serialize", $ls->userDates))))--}}
         <div class="count mini-left">{{ count($ls)}}</div>
            <div class="short-left" id="user_id_{{ $ls->user_id }}"></div>
                 <div class="timer long-right">

                 </div>
            <div class="break-it"></div>
            @endforeach
        @endif
        <div class="break"></div>
        <div>
            <div class="short-left">Total:&nbsp;<span id="total"></span>&nbsp;Logins</div>
            <div class="mini-left"></div>
            <div class="midi-left"></div>
            <div class="long-right"></div>
        </div>
    </div>
</div>
</div>
    @section('scripts')
    @parent
        <script>
            var error = '',
                locale = '{{App::getLocale()}}',
                    formOkClass = 'opac-form-true',
                    formUnit = '.head-unit-form',
                    triggerFormUnit = '.trigger-unit-form',
                    settings = {{$settings}},
                    loginUsers = JSON && JSON.parse('{{ $loginStats }}') || $.parseJSON('{{ $loginStats }}'),
                    start = loginUsers[0].created_at,
                    end = loginUsers[loginUsers.length - 1].created_at,
                    admin_stats = '{{URL::to('admin/stats')}}';
        </script>
        <script src="/assets/js/TimeLiner.js"></script>
    <script>
        $(document).ready(function(){
            $('#loginDataContent').timeLiner();
        });
    </script>
        <script src="/assets/js/admin.js"></script>
    @stop

@stop
