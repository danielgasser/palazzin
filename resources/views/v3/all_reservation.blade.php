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
        <table id="table_all_reservations" class="table_all_reservations" width="100%">
            <thead>
            <tr>
                <th class="0 child">{{trans('reservation.guests.title')}}</th>
                <th class="1" id="arrival">{{trans('reservation.arrival')}}</th>
                <th class="2" id="depart">{{trans('reservation.depart')}}</th>
                <th class="3" id="total_nights">{{trans('reservation.guests.total_nights')}}</th>
                <th class="4" id="total_all_bill">{{trans('bill.total_all_bill')}}</th>
                <th class="5" id="reservation_guest_num">{{trans('validation.attributes.reservation_guest_num')}}</th>
                <th class="6" id="edit">{{trans('dialog.edit')}}</th>
                <th class="7" id="delete">{{trans('dialog.delete')}}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    @include('logged.dialog.delete_reservation')
    @include('logged.dialog.deleted_reservation')
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
            console.log('scrolling', event.target, event);
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
            reservations = JSON.parse('{!!$userRes!!}'),
            guestTitle = '{{trans('reservation.guest_many_no_js.one')}}: ',
            res_lang = JSON.parse('{!!json_encode(trans('reservation'))!!}'),
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
        var reservationTable;
        function format ( d ) {
            if (d.guests.length === 0) return '<table id="allGuestTable" cellpadding="6" style="padding-left:50px; width: 100%" class="table_all_reservations dataTable no-footer dtr-inline">' +
                '<thead>' +
                '<tr>' +
                '<th>' + res_lang.guest_many[3] + '</th>' +
                '</tr>' +
                '</thead></table>';
            let rowBack,
                table = '<table id="allGuestTable" cellpadding="6" style="padding-left:50px; width: 100%" class="table_all_reservations dataTable no-footer dtr-inline">' +
                    '<thead>' +
                        '<tr>' +
                            '<th>' + res_lang.arrival_departure + '</th>' +
                            '<th>' + res_lang.nights + '</th>' +
                            '<th>' + res_lang.guests.number + ' ' + res_lang.guests.title + '</th>' +
                            '<th>' + res_lang.guests.role + '</th>' +
                            '<th>' + res_lang.guests.tax_night + '</th>' +
                            '<th>' + res_lang.guests.total + '</th>' +
                '<th>' + res_lang.guests.total_nights + '</th>' +
                        '</tr>' +
                    '</thead>' +
                    '<tbody>';
                $.each(d.guests, function (i, n) {
                    rowBack = (i % 2 === 0) ? 'even' : 'odd';
                    table += '<tr class="' + rowBack + '">' +
                        '<td>' + n.guest_started_at + '/' + n.guest_ended_at + '</td>' +
                        '<td>' + n.guest_night + '</td>' +
                        '<td>' + n.guest_number + '</td>' +
                        '<td>' + rolesTrans[n.guest_tax_role_id] + '</td>' +
                        '<td>' + n.guest_tax + '</td>' +
                        '<td>' + (n.guest_tax * n.guest_night).toFixed(2) + '</td>' +
                        '<td>' + (n.guest_night * n.guest_number) + '</td>' +
                        '</tr>'
                });
                table += '</tbody></table>';
            return table;
        }
        $(document).ready(function () {
            console.log(reservations)
            let sum = 0,
                today = new Date(),
            old_id;
            today.setHours(23, 59, 59, 999);
                reservationTable = $('#table_all_reservations').DataTable({
                    data: reservations,
                    autoWidth: true,
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
                    responsive: true,
                    "columns": [
                        {
                            "className":      'details-control',
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": '<i class="fa fa-chevron-down" aria-hidden="true"></i>',
                            width: '1%'
                        },
                    ],
                    columnDefs: [
                        {
                            targets: [1],
                            data: 'reservation_started_at',
                            render: function (data, type, full, meta) {
                                if (type === 'sort') {
                                    let d_string = data.split('.'),
                                        d = new Date(d_string[2], (d_string[1] - 1), d_string[0], 0, 0, 0)
                                    return d.getTime();
                                }
                                return data;
                            }
                        },
                        {
                            targets: [2],
                            data: 'reservation_ended_at',
                            render: function (data, type, full, meta) {
                                if (type === 'sort') {
                                    let d_string = data.split('.'),
                                        d = new Date(d_string[2], (d_string[1] - 1), d_string[0], 0, 0, 0)
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
                            data: null,
                            render: function (data) {
                                let d_string = data.reservation_ended_at.split('.'),
                                    d = new Date(d_string[2], (d_string[1] - 1), d_string[0], 0, 0, 0);
                                if (d.getTime() < today.getTime()) {
                                    return '';
                                }
                                return '<form id="edit_table_all_reservations_' + data.id + '" method="post" action="' + urlTo + '/edit_reservation/' + data.id + '">' +
                                    '{{ csrf_field() }}' +
                                    '<button title="' + langDialog.edit + '" class="btn btn-danger btn-v3 show_reservation" id="edit_reservation_' + data.id + '"><i class="fas fa-edit"></i></button>' +
                                    '</form>';
                            }
                        },
                        {
                            targets: [7],
                            data: null,
                            render: function (data) {
                                return '<form id="delete_table_all_reservations_' + data.id + '" method="post" action="' + urlTo + '/delete_reservation/">' +
                                    '{{ csrf_field() }}' +
                                    '<button title="' + langDialog.delete + '" class="btn btn-danger btn-v3 show_reservation" id="delete_reservation_' + data.id + '"><i class="fas fa-trash"></i></button>' +
                                    '</form>';
                            }
                        },
                    ],
                    order: [ 1, 'desc' ],
                    ordering: true,
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

                });
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
        $('#table_all_reservations tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = reservationTable.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                tr.children().first().html('<i class="fa fa-chevron-down" aria-hidden="true"></i>')
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                tr.children().first().html('<i class="fa fa-chevron-up" aria-hidden="true"></i>')
            }
        } );
    </script>

@stop
@stop
