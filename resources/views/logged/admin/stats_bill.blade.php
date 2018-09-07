@extends('layout.master')
@section('content')
    <div id="menu_stats">
        <h1>{!!trans('admin.stats_bill.title')!!} <span id="stats_title"></span></h1>
        {{-- @include('layout.stats_menu')--}}
        <div id="stats_select_menu">
            @include('layout.stats_select')
        </div>
    </div>
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
            var settings = {!!App::make('GlobalSettings')->getSettings()!!},
                    allReservations = $.parseJSON('<?php echo json_encode($allReservations) ?>'),
                    checkedYear = [],
                    all_charts = [],
                    showYear = [],
                    monthColors = {!!json_encode($monthColors)!!},
                    yearColorsSet = {!!json_encode($yearColors)!!},
                    yearColors = {},
                    langCalendar = {!!json_encode(Lang::get('calendar.month-names'))!!},
                    langDialog = {!!json_encode(Lang::get('dialog'))!!},
                chart;
        </script>
    <script>
        $(document).ready(function () {
            'use strict';
            $("[name^='year']").bootstrapToggle({
                on: 'An',
                off: 'Aus'
            });
            $('[name^="year"]').on('change', function (event, state) {
                checkedYear = [];
                showYear = [];
                $.each($('[name^="year"]'), function (i, n) {
                    if ($(n).is(':checked')) {
                        checkedYear.push(n.value + '-%');
                        showYear.push(n.value);
                    }
                    yearColors[n.value] = yearColorsSet[i];
                });
                checkedYear.sort();
                showYear.sort();
                $('#asPDF').hide();
            });
            $(document).on('click', '#getYears', function () {
                if (checkedYear.length === 0) {
                    $('[name^="year"]').trigger('change');
                }
                window.getStatsData('/admin/stats_bill_total_year', checkedYear, window.fillTable);
            });
        })
    </script>

        <script src="/assets/min/js/admin.min.js"></script>
        <script src="/assets/js/stats/stats.js"></script>
        <script src="/assets/js/stats/stats_bill.js"></script>
@stop

@stop