@extends('layout.master')
@section('header')
    @parent
    <style>
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: 1px solid #333;

        }
        #all-year-tables>.table>tbody>tr>td , #total-total-tables>.table>tbody>tr>td {
            font-size: 17px;
        }

    </style>
@stop
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
        <script src="{{asset('libs/highcharts/highcharts.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-data.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-3d.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-export.js')}}"></script>
        <script src="{{asset('js/stats.min.js')}}"></script>
        <script src="{{asset('js/stats_bill.min.js')}}"></script>
@stop

@stop
