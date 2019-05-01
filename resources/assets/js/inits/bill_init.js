$.fn.dataTable.ext.order['dom-text'] = function  (settings, col) {
    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
        return $('#' + td.id).children('input').val();
    });
};
var billTable,
    dataTableSettings = {
        dataSrc: '',
        stripeClasses: [
            'odd',
            'even'
        ],
        responsive: false,
        autoWidth: true,
        fixedHeader: {
            header: true,
            footer: false
        },
        sDom: '<"top"if>',
        language: {
            emptyTable: 'Keine Rechungen vorhanden',
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            search: window.langDialog.search,
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu,
            infoFiltered:   "(gefiltert von _MAX_ Total Einträgen)"
        },
        order: [
            2,
            'desc'
        ],
        paging: false,
        columnDefs: [
            {
                targets: [0],
                responsivePriority: 1,
                class: 'details',
                visible: true,
                orderable:      false,
                data:           null,
                defaultContent: ''
            },
            {
                targets: [1],
                responsivePriority: 1,
                data: 'bill_no'
            },
            {
                targets: [2],
                responsivePriority: 2,
                visible: true,
                orderable: true,
                data: 'bill_bill_date',
                render: function (data, type, full, meta) {
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
                responsivePriority: 2,
                visible: true,
                orderable: true,
                data: 'bill_total',
                type: 'numeric-comma',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d = data.split(' ');
                        return parseFloat(d[1])
                    }
                    return data;
                }
            },
            {
                targets: [4],
                responsivePriority: 3,
                visible: true,
                orderable: true,
                sortable: true,
                data: 'user_first_name user_name',
                type: 'string',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        return $(data).html();
                    }
                    return data
                }
            },
            {
                targets: [5],
                responsivePriority: 4,
                visible: true,
                orderable: false,
                data: 'bill_due'
            },
            {
                targets: [6],
                responsivePriority: 5,
                orderable: true,
                data: 'bill_paid',
                render: function (data, type, full, meta) {
                    let val = $('#' + data.split(' ')[7].split('"')[1]).val();
                    if (type === 'sort') {
                        let d_string = val.split('.'),
                            d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
            {
                targets: [7],
                responsivePriority: 7,
                visible: true,
                orderable: false
            },
            {
                targets: [8],
                responsivePriority: 8,
                visible: true,
                orderable: true,
                data: 'bill_resent_date',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d_string = data.split('.'),
                            d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
        ],
        initComplete: function () {
            this.api().columns(5).every( function () {
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
    dataTableSettingsUser = {
        dataSrc: '',
        stripeClasses: [
            'odd',
            'even'
        ],
        responsive: false,
        autoWidth: true,
        fixedHeader: {
            header: true,
            footer: true
        },
        language: {
            emptyTable: 'Keine Rechungen vorhanden',
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            search: window.langDialog.search,
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu,
            infoFiltered:   "(gefiltert von _MAX_ Total Einträgen)"
        },
        order: [
            1,
            'desc'
        ],
        paging: false,
        columnDefs: [
            {
                targets: [0],
                responsivePriority: 1,
                class: 'details',
                visible: true,
                orderable:      false,
                data:           null,
                defaultContent: ''
            },
            {
                targets: [1],
                responsivePriority: 1,
                data: 'bill_no'
            },
            {
                targets: [2],
                responsivePriority: 2,
                visible: true,
                orderable: true,
                data: 'bill_bill_date',
                render: function (data, type, full, meta) {
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
                responsivePriority: 2,
                visible: true,
                orderable: true,
                data: 'bill_total',
                type: 'numeric-comma',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d = data.split(' ');
                        return parseFloat(d[1])
                    }
                    return data;
                }
            },
            {
                targets: [4],
                responsivePriority: 3,
                visible: true,
                orderable: true,
                sortable: true,
                data: 'user_first_name user_name',
                type: 'string',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        return $(data).html();
                    }
                    return data
                }
            },
            {
                targets: [5],
                responsivePriority: 4,
                visible: true,
                orderable: false,
                data: 'bill_due'
            },
            {
                targets: [6],
                responsivePriority: 5,
                orderable: true,
                data: 'bill_paid'
            },
        ],
        initComplete: function () {
            this.api().columns(5).every( function () {
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
            paid = window.billPaid[0]
        } else {
            url = 'paid';
            dbDate = deFormatDate(dbDate, '.');
            paid = window.billPaid[1]
        }
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/admin/bills/' + url,
            data: {
                id: id,
                bill_paid: dbDate,
                paid_at: $('#paidAt_' + id).val()
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                let el = $('#paidAt_' + id);
                $('#paid_' + id).html(paid);
                el.val(data.paid);
                if (data.paid === null) {
                    $('#re_send_' + id).html(
                        '<button class="btn btn-default" id="bill_sent_' + id + '">' + window.langBill  + '</button>'
                    );
                } else {
                    $('#bill_sent_' + id).remove();
                }
                $('#year').trigger('change');
                return false;
            }
        });
    },
    resendBill = function (id) {
        $.ajax({
            type: 'POST',
            url: '/admin/send_bill',
            data: {
                id: id,
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                $('#re_sent_' + id)
                    .html(data.resent)
                    .attr('data-sort', data.resent_data_sort);
                return false;
            }
        });
    },
    fillBillTotals = function (d) {
        let data = $.parseJSON(d),
            unpaid = data.unpaid,
            paid = data.paid,
            total = data.total;

        $('#paid').html('CHF ' + paid);
        $('#total').html('CHF ' + total);
        $('#unpaid').html('CHF ' + unpaid);
    },
    getBillTotals = function (year) {
        let adminRoute = (window.route.indexOf('user/bills') > -1) ? '' : '/admin';
    $.ajax({
            type: 'GET',
            url: adminRoute + '/bills/totals',
            data: {
                year: year,
                user_id: (window.route.indexOf('user/bills') > -1)
            },
            success: function (data) {
                fillBillTotals(data);
            }
        })
    };
$(document).ready(function () {
    if (window.isUserBill === '1') {
        billTable = $('#bills').DataTable(dataTableSettingsUser);
    } else {
        billTable = $('#bills').DataTable(dataTableSettings);
    }
    datePickerInit();
});
$(document).on('click', '.paginate_button>a', function () {
    datePickerInit();
});
$(document).on('click', '[id^="bill_sent_"]', function () {
    let id = $(this).attr('id').split('_')[2];
        resendBill(id);
});
$(document).on('change', '#year', function () {
   getBillTotals(this.value);
   if (this.value !== 'all') {
       let filter = $('#bills_filter').find('input');
       filter.val(this.value);
       filter.trigger('keyup');
   }
});

$(document).ready(function () {
   getBillTotals('all');
});
