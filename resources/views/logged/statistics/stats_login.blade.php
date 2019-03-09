@extends('layout.master')
@section('content')
<div id="chart_div" style="min-width: 310px; height: 400px; margin: 0 auto;">
</div>
    <div id="mPDF_Print">
        <div id="chart_div_four" style="min-width: 310px; height: auto; margin: 0 auto;">
        </div>
        <div id="chart_div_three" style="min-width: 310px; height: auto; margin: 0 auto;">
        </div>
        <div id="chart_div" style="min-width: 310px; height: 400px; margin: 20px auto;">
        </div>
        <div id="chart_div_two" style="min-width: 310px; height: 400px; margin: 20px auto;">
        </div>
        <table id="datatable" class="table table-striped table-hover table-stats">
        </table>
    </div>
@section('scripts')
        @parent
        <script>
            var langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                checkedYear = [],
                yearColorsSet = {!!json_encode($yearColors)!!},
                yearColors = {},
                showYear = [],
                    route = '{{Request::url()}}';

        </script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <link href="/assets/js/libs/bootstrap_switch/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
        <script src="/assets/js/libs/bootstrap_switch/js/bootstrap-switch.js"></script>
        <script>
    </script>

        <script src="/assets/js/stats/stats.js"></script>
        <script src="/assets/js/stats/stats_login.js"></script>
    @stop

@stop
