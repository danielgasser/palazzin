@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp_reservation.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>

@stop
@section('content')
    <a name="top"></a>
    <div id="reservationInfo">
        <h4></h4>
    </div>
    <div id="upper">
        <table id="table_all_reservations" class="table_all_reservations">
            <thead>
            <tr>
                <th class="0" id="user_name">{{trans('userdata.user_name')}}</th>
                <th class="1" id="arrival">{{trans('reservation.arrival')}}</th>
                <th class="2" id="depart">{{trans('reservation.depart')}}</th>
                <th class="3" id="total_nights">{{trans('reservation.guests.total_nights')}}</th>
                <th class="4" id="total_all_bill">{{trans('bill.total_all_bill')}}</th>
                <th class="5" id="reservation_guest_num">{{trans('validation.attributes.reservation_guest_num')}}</th>
                <th class="6" id="reservation_bill_sent">{{trans('bill.bill_sent')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.no_delete_reservation')
    {{--
     @include('logged.dialog.guest_nan')
     @include('logged.dialog.night_nan')
     @include('logged.dialog.cross_reserv')
     @include('logged.dialog.cross_reserv_user_list')
     @include('logged.dialog.not_invited')
     --}}
@section('scripts')
    @parent
    <script>
        document.addEventListener('scroll', function (event) {
            if (event.target.id === 'idOfUl') { // or any other filtering condition
            }
        }, true);
    </script>
    <script>
        var startDate,
            endDate,
            rolesTaxes = {!! $roles !!},
            langDialog = {!!json_encode(Lang::get('dialog'))!!},
            rolesTrans = JSON.parse('{!!json_encode($rolesTrans)!!}'),
            fullMonthNames = JSON.parse('{!!json_encode(trans('calendar.month-names'))!!}'),
            datePickersStart = [],
            reservations = JSON.parse('{!!$allRes!!}'),
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            datePickersEnd = [],
            token = '{{ csrf_token() }}',
            reservationStrings = JSON.parse('{!!json_encode(trans('reservation'))!!}');
    </script>
    {{-- <script src="{{asset('assets/js/v3/global_functions/funcs.js')}}"></script>--}}
    <script src="{{asset('assets/js/v3/V3Reservation.js')}}"></script>
    <script>
       // $.holdReady(true);
        //V3Reservation.writeLocalStorage(periods);
        //V3Reservation.createTimeLine(periods);
    </script>
    <script src="{{asset('assets/js/v3/events.js')}}"></script>
    <script>
        $(document).ready(function () {
            console.log(reservations)
            let sum = 0,
                reservationTable = $('#table_all_reservations').DataTable({
                    data: reservations,
                    responsive: true,
                    autoWidth: false,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    columnDefs: [
                        {
                            targets: [0],
                            data: 'user_first_name',
                            render: function (data, type, full, meta) {
                                return data + ' ' + full.user_name;
                            }
                        },
                        {
                            targets: [1],
                            data: 'reservation_started_at',
                            render: function (data, type, full, meta) {
                                console.log(type);
                                if (type === 'sort') {
                                    let d_string = data.split('.'),
                                        d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                                    return d.getTime();
                                }
                                return data;
                            }
                        },
                        {
                            targets: [2],
                            data: 'reservation_ended_at',
                            render: function (data, type, full, meta) {
                                console.log(type);
                                if (type === 'sort') {
                                    let d_string = data.split('.'),
                                        d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                                    return d.getTime();
                                }
                                return data;
                            }
                        },
                        {
                            targets: [3],
                            data: 'reservation_nights',
                        },
                        {
                            targets: [4],
                            data: null,

                            render: function (data, type, full, meta) {
                                let total = 0.0;
                                $.each(data.guests, function (i, n) {
                                    total += n.guest_night * n.guest_number * parseFloat(n.guest_tax)
                                });
                                return (total === 0.00) ? '-' : total.toFixed(2);
                            }

                        },
                        {
                            targets: [5],
                            data: null,
                            render: function (data, type, full, meta) {
                                let total = 0;
                                $.each(data.guests, function (i, n) {
                                    total += n.guest_number
                                });
                                return (total === 0) ? '-' : total;
                            }
                        },
                        {
                            targets: [6],
                            data: 'reservation_bill_sent',
                            render: function (data, type, full, meta) {
                                return (data === 0) ? 'Nein' : 'Ja';
                            }
                        },
                    ],
                    language: {
                        paginate: {
                            first: '{{trans('pagination.first')}}',
                            previous: '{{trans('pagination.previous')}}',
                            next: '{{trans('pagination.next')}}',
                            last: '{{trans('pagination.last')}}',
                        },
                        info: '{{trans('pagination.info')}}',
                        sLengthMenu: '{{trans('pagination.length_menu')}}',
                        search: '{{trans('dialog.search')}}'
                    },

                }),
                old_id;
            old_id = 0;
            $.each($('[id^="number_nights_"]'), function (i, n) {
                let r_id = $(n).attr('id').split('_')[3],
                    g_id = $(n).attr('id').split('_')[2];
                if (g_id !== old_id) {
                    sum = 0;
                }
                old_id = g_id;
                if (parseFloat($('#reservation_guest_price_' + g_id + '_' + r_id).val()) > 0) {
                    sum += (parseInt($(n).val(), 10) * parseFloat($('#reservation_guest_price_' + g_id + '_' + r_id).val()) * parseInt($('#reservation_guest_num_' + g_id + '_' + r_id).val(), 10));
                }
                $('#price_' + g_id + '_' + r_id).val(sum.toFixed(2));
            });
            old_id = 0;
            $.each($('[id^="price_"]'), function (i, n) {
                let id = $(n).attr('id').split('_')[2];
                if (id !== old_id) {
                    sum = 0;
                }
                old_id = id;
                sum += parseFloat($(n).val());
                $('#reservation_costs_total_' + id).html(sum.toFixed(2));
            });
        })
    </script>

@stop
@stop
