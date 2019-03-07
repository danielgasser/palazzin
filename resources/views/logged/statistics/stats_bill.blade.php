@extends('layout.master')
@section('content')
<div id="mPDF_Print">
    <div id="datatable-short" style="    float: none;
    width: 100%;"></div>

    <div id="all-year-tables">

    </div>
    <div id="total-total-tables">

    </div>
    <div id="chart_div_total_year" style="width: 98%"></div>
    <div id="monthTable"></div>
</div>
@section('scripts')
        @parent
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <link href="/assets/js/libs/bootstrap_switch/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
        <script src="/assets/js/libs/bootstrap_switch/js/bootstrap-switch.js"></script>
        <script>
            var allReservations = $.parseJSON('{!!  json_encode($allReservations) !!}'),
                    checkedYear = [],
                    all_charts = [],
                    showYear = [],
                    monthColors = {!!json_encode($monthColors)!!},
                    yearColorsSet = {!!json_encode($yearColors)!!},
                    yearColors = {},
                    langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                chart;
        </script>
        <script src="/assets/js/stats/stats.js"></script>
        <script src="/assets/js/stats/stats_bill.js"></script>
@stop

@stop
