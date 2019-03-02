@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/js/v3/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('assets/js/v3/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css')}}/bootstrap-datepicker.css"
          rel="stylesheet" media="screen" type="text/css">
    @stop
@section('content')
    </div>
    <div class="row">
        <table id="bills">
            <thead>
                <tr>
                    <th>{{trans('bill.billno')}}</th>
                    <th>{{trans('bill.date')}}</th>
                    <th>{{trans('bill.total_bill')}}</th>
                    <th>{{trans('profile.names')}}</th>
                    <th>{{trans('bill.paid')}}</th>
                    <th>{{trans('bill.paid_at')}}</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
            @foreach($allBills as $b)

                <tr id="{{$b->id}}">

                    <td>{{$b->bill_no}}</td>
                    <td data-sort="{{$b->bill_bill_date}}">{{$b->bill_bill_date_show}}</td>
                    <td data-sort="{{$b->bill_total}}">{{$b->bill_currency}} {{$b->bill_total}}</td>
                    <td>
                        <a href="{{URL::to('user/profile/' . $b->reservation->user_id)}}">{{$b->user->user_first_name}} {{$b->user->user_name}}</a>
                    </td>
                    <td id="paid_{{$b->id}}">{{(isset($b->bill_paid)) ? trans('bill.paid') : trans('bill.un_paid') }}</td>
                    <td data-sort="{{$b->bill_paid}}" id="datePicker_{{$b->id}}">
                        <input class="form-control input-sm show_reservation" placeholder="{{trans('bill.un_paid')}}" readonly="readonly" name="paidAt_{{$b->id}}" id="paidAt_{{$b->id}}" value="{{$b->bill_paid_show}}@" />
                    </td>
                    <td>
                        <a class="bill_path" target="_blank" href="{{URL::to('/')}}{{$b->bill_path}}">{{$b->bill_no}}.pdf</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>{{trans('bill.billno')}}</th>
                <th>{{trans('bill.date')}}</th>
                <th>{{trans('bill.total_bill')}}</th>
                <th>{{trans('profile.names')}}</th>
                <th>{{trans('bill.paid')}}</th>
                <th>{{trans('bill.paid_at')}}</th>
                <th>PDF</th>
            </tr>
            </tfoot>
        </table>
    </div>

@section('scripts')
    @parent
    <script>
        var billTable,
            dataTableSettings = {
                dataSrc: '',
                responsive: true,
                autoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                language: {
                    paginate: {
                        first: '{{trans('pagination.first')}}',
                        previous: '{{trans('pagination.previous')}}',
                        next: '{{trans('pagination.next')}}',
                        last: '{{trans('pagination.last')}}'
                    },
                    search: '{{trans('dialog.search')}}',
                    info: '{{trans('pagination.info')}}',
                    sLengthMenu: '{{trans('pagination.length_menu')}}'
                },
                columnDefs: [
                    { "orderable": false, "targets": 4 }
                ],
                initComplete: function () {
                    this.api().columns(4).every( function () {
                        var column = this;
                        var select = $('<select class="form-control input-sm show_reservation"><option value=""></option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                                datePickerInit();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            },
            datePickerSettings = {
                format: "dd.mm.yyyy",
                weekStart: 1,
                todayBtn: "linked",
                clearBtn: true,
                language: 'de',
                calendarWeeks: true,
                autoclose: true,
                todayHighlight: true,
                immediateUpdates: true,
            },
        datePickers = [],
        datePickerInit = function () {
            $.each($('[id^="paidAt_"]'), function (i, n) {
                let id = $(n).attr('id');
                datePickers.push({
                    [id]: $(n).datepicker(datePickerSettings)
                });
                $(n).datepicker('setDate', new Date($(n).parent('td').attr('data-sort'))).on('hide', function (e) {
                    console.log(e)
                    payBill(e, $(n).val());
                });
            })
        },
        deFormatDate = function (d, sep) {
            let tmp = d.split(sep),
                arr = [],
                month = parseInt(tmp[1], 10),
                day = parseInt(tmp[0], 10);
            arr[2] = (day < 10) ? '0' + day : day;
            arr[1] = (month < 10) ? '0' + month : month;
            arr[0] = tmp[2];
            return arr.join('-');
        },

        payBill = function (e, dbDate) {
            var id = e.target.id.split('_')[1],
                url,
                paid;
            if (dbDate === '') {
               url = 'unpaid';
               paid = '{{trans('bill.un_paid')}}'
            } else {
                url = 'paid';
                dbDate = deFormatDate(dbDate, '.');
                paid = '{{trans('bill.paid')}}'
            }
            e.preventDefault();
            $.ajax({
                type: 'GET',
                url: '/admin/bills/' + url,
                data: {
                    id: id,
                    bill_paid: dbDate
                },
                success: function (data) {
                    window.unAuthorized(data);
                    let el = $('#paidAt_' + id);
                    $('#paid_' + id).html(paid);
                    el.val(data.paid);
                }
            });

        };
    </script>
    <script src="{{asset('assets/js/inits/bill_init.js')}}"></script>
    <script>
        $(document).ready(function () {
            billTable = $('#bills').DataTable(dataTableSettings);
            datePickerInit();
        });
        $(document).on('click', '.paginate_button>a', function () {
            console.log(this);
            datePickerInit();
        })
    </script>
@stop

@stop
