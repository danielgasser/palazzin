@extends('layout.master')
@section('header')
    @parent
    <style>
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: 1px solid #333;

        }

    </style>
@stop
@section('content')
<div id="mPDF_Print">
    <table id="datatable-short-calendar" class="table" style="width: 100%">
        <thead style="display: none">
        <tr>
            <th></th>
            <?php $i = 0; ?>
            @foreach(Lang::get('calendar.month-names') as $c)
                <th id="month_long_{{$i}}"><h6>{{$c}}</h6></th>
                <?php $i++ ?>
            @endforeach
            <th><h6>Total</h6></th>
        </tr>
        </thead>
        <tbody id="data-short">
        </tbody>
    </table>
    <div id="chart_div_total" style="min-width: 600px;    width: 100%; margin: 0 auto;">
    </div>
    <div id="datatable-div">
    </div>
    <div id="chart_div" style="min-width: 600px; margin: 10px auto;">
    </div>
</div>
@section('scripts')
        @parent
        <script>
            var langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                    checkedYear = [],
                    all_charts = [],
                    showYear = [],
                    monthColors = {!!json_encode($monthColors)!!},
                    yearColorsSet = {!!json_encode($yearColors)!!},
                    yearColors = {},
                    monthDays = [];
        </script>

        <script src="{{asset('libs/highcharts/highcharts.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-data.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-3d.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-export.js')}}"></script>
        <script src="{{asset('js/stats.min.js')}}"></script>
        <script src="{{asset('js/stats_calendar.min.js')}}"></script>

@stop

@stop
