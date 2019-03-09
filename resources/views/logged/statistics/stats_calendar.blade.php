@extends('layout.master')
@section('content')
<div id="mPDF_Print">
    <table id="datatable-short" class="table table-striped table-hover table-stats">
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
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <link href="/assets/js/libs/bootstrap_switch/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
        <script src="/assets/js/libs/bootstrap_switch/js/bootstrap-switch.js"></script>
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

        <script src="/assets/js/stats/stats.js"></script>
        <script>

        </script>
        <script src="/assets/js/stats/stats_calendar.js"></script>

@stop

@stop
