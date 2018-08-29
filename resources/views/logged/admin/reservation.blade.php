@extends('layout.master')
@section('content')
    <div id="resAllHead">
        <h3>{!!trans('navigation.' . Route::getFacadeRoot()->current()->uri())!!}</h3>
        <div id="res_searcher">
            <table style="width: 100%;">
                @include('layout.search_res_tables', ['users' => $users])
            </table>
        </div>
    </div>
    @if(sizeof($allReservations) > 0)
    <div class="table-responsive" style="min-height: 500px;">
        <table id="allReservations" class="table tablesorter">
            <thead>
                <tr>
                    <th class="sortCols" id="user_first_name">{!!trans('userdata.user_first_name')!!}</th>
                    <th class="sortCols" id="user_name">{!!trans('userdata.user_name')!!}</th>
                    <th class="sortCols" id="reservation_started_at">{!!trans('reservation.arrival')!!}</th>
                    <th class="sortCols" id="reservation_ended_at">{!!trans('reservation.depart')!!}</th>
                    <th class="sortCols" id="reservation_nights">{!!trans('reservation.nights')!!}</th>
                    <th>{!!trans('reservation.guests.title')!!}</th>
                </tr>
            </thead>
            <tbody id="keeperData">
                @foreach($allReservations as $r)
                <tr id="reserv_{!!$r->id!!}">
                     <td title="{!!trans('reservation.goto_title_singular')!!}">{!!$r->user_first_name!!}</td>
                     <td title="{!!trans('reservation.goto_title_singular')!!}">{!!$r->user_name!!}</td>
                     <td title="{!!trans('reservation.goto_title_singular')!!}" id="currentCalDate_{!!$r->reservation_started_at!!}">{!!$r->reservation_started_at_show!!}</td>
                     <td title="{!!trans('reservation.goto_title_singular')!!}">{!!$r->reservation_ended_at_show!!}</td>
                     <td title="{!!trans('reservation.goto_title_singular')!!}">{!!$r->reservation_nights!!}</td>
                     <td title="{!!trans('reservation.goto_title_singular')!!}">
                        @if(sizeof($r->guests) > 0)
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
                                    <td>{!!$guest->role_code!!}</td>
                                    <td>{!!$guest->role_tax_night!!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            {!!trans('reservation.guest_many_no_js.none')!!}
                        @endif
                    </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
    @else
        <h3>{!!trans('errors.no-data', ['n' => 'e', 'd' => 'Reservierungen'])!!}</h3>
    @endif

    @section('scripts')
    @parent
    <script >
        var ss = 'ASC',
                a = '#allReservations',
                locale = '{!!Lang::get('formats.langlangjs')!!}',
                langDialog = '{!!json_encode(Lang::get('dialog'))!!}',
                langRes = '{!!json_encode(Lang::get('reservation'))!!}',
                cols = $('th'),
                yl = [],
                settings = $.parseJSON('{!!json_encode($settings)!!}'),
                ml = [],
                thatRoute = '{!!Route::getFacadeRoot()->current()->uri()!!}';
    </script>
    <script src="{!!asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')!!}"></script>
    <script src="{!!asset('assets/js/inits/user_reservation.js')!!}"></script>
    <script src="{!!asset('assets/min/js/tables.min.js')!!}"></script>
    <script src="{!!asset('assets/js/inits/search_res_tables_init.js')!!}"></script>
        <script>
            $(document).ready(function () {
                $(a).tablesorter();
            })

        </script>
    @stop

@stop
