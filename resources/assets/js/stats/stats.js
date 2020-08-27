var dataStatsTableOptions = {
        dom: 'Bfrtip',
        columnDefs: [
            {
                targets: [0],
                visible: true,
                orderable: true,
                data: 'reservation_started_at_month',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d_string = full.reservation_started_at.split('.'),
                            d_month = window.langCalendar[d_string[1]],
                            d = new Date(d_string[1], d_month, 1, 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
            {
                targets: [1],
                visible: true,
                orderable: true,
                data: 'user_first_name',
            },
            {
                targets: [2],
                visible: true,
                orderable: true,
                data: 'clan_description',
            },
            {
                targets: [3],
                visible: true,
                orderable: true,
                data: 'family_description',
            },
            {
                targets: [4],
                visible: true,
                orderable: true,
                data: 'reservation_started_at',
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
                targets: [5],
                visible: true,
                orderable: true,
                data: 'reservation_ended_at',
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
                targets: [6],
                visible: true,
                orderable: true,
                data: 'reservation_nights',
            },
            {
                targets: [7],
                visible: true,
                orderable: true,
                data: 'bill',
                type: 'numeric-comma',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        return parseFloat(data)
                    }
                    return data;
                }
            },
            {
                targets: [8],
                visible: true,
                orderable: true,
                data: 'guest',
            },
        ],
        buttons: [
            {
                extend: 'copy',
                text: 'Kopieren',
                className: 'btn btn-default'
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-default'
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-default'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-default'
            },
            {
                extend: 'print',
                text: 'Drucken',
                className: 'btn btn-default'
            }
        ],
        searching: true,
        paging: false,
        sortable: true,
        ordering: true,
        language: {
            emptyTable: 'Keine Daten vorhanden',
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
        responsive: true,
        autoWidth: true,
        fixedHeader: {
            header: false,
            footer: false
        }
    },
    getStatsData = function (url, year, callback) {
    'use strict';
    if (year.length === 0) {
        return false;
    }
    var yearLabel = [];
    $.each(year, function (i, n) {
        yearLabel.push(window.parseInt(n, 10));
    });
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            year: year
        },
        success: function (d) {
            if (url.indexOf('stats_chron') > -1) {
                callback(d[0], year, d[1], d[2], d[3]);
            } else {
                callback(d, year);
            }
            $('#stats_title').text(yearLabel.join(', '));
            $.each($('[id^="dataTable_"]'), function () {
                if ($.fn.DataTable.isDataTable(this)) {
                    $(this).destroy();
                }
                $(this).DataTable(dataStatsTableOptions);
            })
        }
    });
};
