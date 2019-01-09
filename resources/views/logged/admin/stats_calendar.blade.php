@extends('layout.master')
@section('content')
<div id="menu_stats">
    <h1>{{trans('admin.stats_calendar.title')}} <span id="stats_title"></span></h1>
    {{-- @include('layout.stats_menu')--}}
    <div id="stats_select_menu">
        @include('layout.stats_select')
    </div>
</div>
<div id="mPDF_Print">
    <table id="datatable-short" class="table table-striped table-hover tablesorter table-stats">
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
                    langDialog = {!!json_encode(Lang::get('dialog'))!!},
                    monthDays = [];
            Highcharts.exportCharts = function (charts, options) {
                'use strict';
                // Merge the options
                options = Highcharts.merge(Highcharts.getOptions().exporting, options);

                // Post to export server
                window. Highcharts.post(options.url, {
                    filename: options.filename || 'chart',
                    type: options.type,
                   // width: options.width,
                    svg: Highcharts.getSVG(charts)
                });
            };
            Highcharts.getSVG = function (charts, options) {
                'use strict';
                var svgArr = [],
                        top = 0,
                        width = 0,
                        svgString = '';

                $.each(charts, function (i, chart) {
                    if (!$.isEmptyObject(chart)) {
                        var svg = chart.getSVG();
                        svg = svg.replace('<svg', '<g transform="translate(0,' + top + ')" ');
                        svg = svg.replace('</svg>', '</g>');

                        top = chart.chartHeight;
                        width = Math.max(width, chart.chartWidth);
                        svgString += '<svg height="' + top + '" width="' + width + '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + svg + '</svg>'

                        console.log(top, width)
                    }
                    //svgArr.push(svg);
                });
                //$.each(svgArr, function (i, n) {
                //    //svgString += '<svg height="' + top + '" width="' + width + '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + n + '</svg>'
                //});
                return svgString;//'<svg height="' + top + '" width="' + width + '" version="1.1" xmlns="http://www.w3.org/2000/svg">' + svgArr.join('') + '</svg>';
            };

        </script>

        <script src="/assets/min/js/admin.min.js"></script>
        <script src="/assets/js/stats/stats.js"></script>
        <script>
            $(document).ready(function(){
                'use strict';
                for (var i = 1; i < 32; i += 1) {
                    monthDays.push(i);
                }
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
                })

            });
            $(document).on('click', '#getYears', function () {
                if (checkedYear.length === 0) {
                    $('[name^="year"]').trigger('change');
                }
                window.getStatsData('/admin/stats_calendar_total_day', checkedYear, window.fillTable);
            });
            $(document).on('click', '#bla', function () {
                window.console.log();
            });

        </script>
        <script src="/assets/js/stats/stats_calendar.js"></script>

@stop

@stop
