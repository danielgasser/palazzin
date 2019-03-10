@extends('layout.master')
@section('content')
@section('header')
    @parent
    <style>
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: 1px solid #333;

        }

    </style>
@stop
<div id="mPDF_Print">
    <div id="chart_div_four" style="min-width: 310px; height: auto; margin: 0 auto;">
    </div>
    <div id="chart_div_three" style="min-width: 310px; height: auto; margin: 0 auto;">
    </div>
    <div id="chart_div" style="min-width: 310px; height: 400px; margin: 20px auto;">
    </div>
    <div id="chart_div_two" style="min-width: 310px; height: 400px; margin: 20px auto;">
    </div>
    <div id="datatable">
    </div>
</div>    @section('scripts')
        @parent
        <script>
            var settings = JSON.parse({!!json_encode($settingsJSON)!!}),
                langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                checkedYear = [],
                monthColors = {!!json_encode($monthColors)!!},
                yearColorsSet = {!!json_encode($yearColors)!!},
                showYear = [],
                guestColors = [
                    '#7cb5ec',
                    '#434348',
                    '#90ed7d',
                    '#f7a35c'
                ],
                route = '{{Request::url()}}',
                dataTable;

        </script>
        <script src="{{asset('libs/highcharts/highcharts.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-data.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-3d.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-export.js')}}"></script>
        <script src="{{asset('js/stats.min.js')}}"></script>
        <script src="{{asset('js/stats_cron.min.js')}}"></script>
    @stop

@stop
