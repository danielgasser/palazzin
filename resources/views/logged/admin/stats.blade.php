@extends('layout.master')
@section('content')
<div id="menu_stats">
    <h1>{!!trans('admin.stats_chron.title')!!} <span id="stats_title"></span></h1>
    {{-- @include('layout.stats_menu')--}}
    <div id="stats_select_menu">
        @include('layout.stats_select')
    </div>
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
    <table id="datatable" class="table table-striped table-hover tablesorter table-stats">
    </table>
</div>    @section('scripts')
        @parent
        <script>
            var settings = {!!App::make('GlobalSettings')->getSettings()!!},
                    langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                    checkedYear = [],
                    yearColorsSet = {!!json_encode($yearColors)!!},
                    showYear = [],
                    langDialog = {!!json_encode(Lang::get('dialog'))!!},
                    route = '{!!Request::url()!!}';

        </script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/data.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <link href="/assets/js/libs/bootstrap_switch/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
        <script src="/assets/js/libs/bootstrap_switch/js/bootstrap-switch.js"></script>
        <script src="/assets/js/libs/tableToExcel/tableToExcel.js"></script>
        <script src="/assets/min/js/admin.min.js"></script>
        <script src="/assets/js/stats/stats.js"></script>
        <script src="/assets/js/stats/stats_cron.js"></script>
        <script>
            $(document).ready(function(){
                'use strict';
                $("[name^='year']").bootstrapSwitch({
                    onColor: 'retroorange',
                    onText: 'An',
                    offText: 'Aus'
                });
                $('[name^="year"]').on('switchChange.bootstrapSwitch', function (event, state) {
                    checkedYear = [];
                    $.each($('[name^="year"]'), function (i, n) {
                        if ($(n).is(':checked')) {
                            checkedYear.push(n.value + '-%');
                        }
                    });
                    checkedYear.sort();
                    showYear.sort();
                    $('#asPDF').hide();
                })
            })
            $(document).on('click', '#getYears', function () {
                if (checkedYear.length === 0) {
                    $('[name^="year"]').trigger('switchChange.bootstrapSwitch');
                }
                window.getStatsData('/admin/stats_chron', checkedYear, window.fillTable);
            });
        </script>
    @stop

@stop
