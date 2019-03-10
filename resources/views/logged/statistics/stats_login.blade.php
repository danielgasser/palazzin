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
        <script src="{{asset('libs/highcharts/highcharts.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-data.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-3d.js')}}"></script>
        <script src="{{asset('libs/highcharts/highcharts-export.js')}}"></script>
        <script src="{{asset('js/stats.min.js')}}"></script>
        <script src="{{asset('js/stats_login.min.js')}}"></script>
    @stop

@stop
