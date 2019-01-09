@extends('layout.master')
@section('content')
<h1>{{trans('admin.reservation.title')}}</h1>
<div>
    <div class="table-responsive">
        <table id="allReservations" class="table table-striped table-hover tablesorter">
            <thead>
                @include('layout.search_res_tables')
                <tr>
                    <th id="user_first_name">{{trans('userdata.user_first_name')}}</th>
                    <th id="user_name">{{trans('userdata.user_name')}}</th>
                    <th class="asc" id="reservation_started_at">{{trans('reservation.arrival')}}</th>
                    <th class="asc" id="reservation_ended_at">{{trans('reservation.depart')}}</th>
                    <th id="reservation_nights">{{trans('reservation.nights')}}</th>
                    <th>{{trans('reservation.guests.title')}}</th>
                </tr>
            </thead>
            <tbody id="keeperData">
            @foreach($allReservations as $r)
                <tr>
                     <td>{{$r->user_first_name}}</td>
                     <td>{{$r->user_name}}</td>
                     <td>{{$r->reservation_started_at_show}}</td>
                     <td>{{$r->reservation_ended_at_show}}</td>
                     <td>{{$r->reservation_nights}}</td>
                     <td>
                        @foreach($r->guests as $guest)
                        {{$guest->guest_started_at_show}} - {{$guest->guest_ended_at_show}}: {{$guest->guest_number}}<br>
                        @endforeach
                    </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
</div>
    @section('scripts')
    @parent
        <script src="{{asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')}}"></script>
        <script src="{{asset('assets/min/js/search_res_tables_init.min.js')}}"></script>
        <script >
            var ss = 'ASC',
                    a = '#allReservations',
                    locale = '{{Lang::get('formats.langlangjs')}}',
                    langDialog = {!!json_encode(Lang::get('dialog'))!!},
                    cols = $('th'),
                    yl = [],
                    settings = {{App::make('GlobalSettings')->getSettings()}},
                    ml = [];
        </script>
    @stop

@stop
