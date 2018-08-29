@extends('layout.master')
@section('content')
<h1>{!!trans('admin.reservation.title')!!}</h1>
<div>{{-- ToDO unused --}}
    <h3>{!!trans('admin.reservation.etc')!!}:ssss</h3>
    <div class="table-responsive">
        <table id="allReservations" class="table table-striped table-hover tablesorter">
            <thead>
            @include('layout.search_res_tables')
                <tr>
                    <th>{!!trans('reservation.arrival')!!}</th>
                    <th>{!!trans('reservation.depart')!!}</th>
                    <th>{!!trans('reservation.nights')!!}</th>
                    <th>{!!trans('reservation.guests.title')!!}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($allReservations as $r)
                <tr>
                     <td>{!!$r->reservation_started_at!!}</td>
                     <td>{!!$r->reservation_ended_at!!}</td>
                     <td>{!!$r->reservation_nights!!}</td>
                     <td>
                        <table class="table" id="allGuests_{!!$r->id!!}">
                            <thead>
                                <tr>
                                    <th>{!!trans('reservation.guests.number')!!}</th>
                                    <th>{!!trans('reservation.guests.role')!!}</th>
                                    <th>{!!trans('reservation.guests.tax_night')!!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($r->guests as $guest)
                                <tr>
                                    <td>{!!$guest->guest_number!!}</td>
                                    <td>{!!$guest->guest_tax_role_id!!}</td>
                                    <td>{!!$guest->guest_tax!!}{{$guest->role_code}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
</div>
    @section('scripts')
    @parent
        <script src="/assets/js/tablesorter/jquery.tablesorter.min.js"></script>
    <script src="{!!asset('assets/js/inits/search_res_tables_init.js')!!}"></script>
    <script >
        var ss = 'ASC',
                a = '#allReservations',
                locale = '{!!Lang::get('formats.langlangjs')!!}',
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                cols = $('th'),
                yl = [],
                settings = {!!App::make('GlobalSettings')->getSettings()!!},
                ml = [];
    </script>
    @stop

@stop