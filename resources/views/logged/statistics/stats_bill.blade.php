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
        <script src="{{asset('js/stats_bill.min.js')}}"></script>
@stop

@stop
