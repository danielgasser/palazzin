var billTable,
    dataTableSettings = {
        dataSrc: '',
        responsive: false,
        autoWidth: true,
        fixedHeader: {
            header: true,
            footer: true
        },
        language: {
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            search: window.langDialog.search,
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu
        },
        order: [
            1,
            'asc'
        ],
        paging: false,
        columnDefs: [
            { "orderable": false, "targets": 4 },
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
                data: 'bill_total'
            },
            {
                targets: [3],
                responsivePriority: 3,
                data: 'user_id'
            },

            {
                targets: [4],
                responsivePriority: 4,
                data: 'bill_due'
            },

            {
                targets: [5],
                responsivePriority: 5,
                orderable: false,
                data: 'bill_paid'
            },

            {
                targets: [6],
                responsivePriority: 6,
                data: 'bill_path'
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
                bill_paid: dbDate
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                let el = $('#paidAt_' + id);
                $('#paid_' + id).html(paid);
                el.val(data.paid);
            }
        });

    };

$(document).ready(function () {
    billTable = $('#bills').DataTable(dataTableSettings);
    datePickerInit();
});
$(document).on('click', '.paginate_button>a', function () {
    datePickerInit();
})
