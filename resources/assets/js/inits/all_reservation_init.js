$(document).ready(function () {
    let sum = 0,
        reservationTable = $('#table_all_reservations').DataTable({
            data: reservations,
            responsive: true,
            autoWidth: true,
            order: [[ 1, "desc" ]],
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
                    data: null,
                    sortable: false,
                    render: function (data, type, full, meta) {
                        var tmp = data.reservation_ended_at.split('.'),
                        d = new Date(tmp[2], (tmp[1] - 1), tmp[0]);
                        d.setDate(d.getDate() + 1);
                        if (window.auth === data.user_id && window.today.getTime() <= d.getTime()) {
                            return '<a style="width: 20px;height: 20px;display: inline-block;border: none;padding: 0;background: transparent;" title="' + window.langDialog.edit + '" class="btn btn-default btn-v3 show_reservation" id="edit_reservation_' + data.id + '" href="edit_reservation/' + data.id + '"><i class="fas fa-edit"></i></a>';
                        }
                        return '';
                    }
                },
                {
                    targets: [7],
                    data: null,
                    sortable: false,
                    render: function (data, type, full, meta) {
                        var tmp = data.reservation_ended_at.split('.'),
                            d = new Date(tmp[2], (tmp[1] - 1), tmp[0]);
                        d.setDate(d.getDate() + 1);
                        if (window.auth === data.user_id && window.today.getTime() <= d.getTime()) {
                            return '<form style="display: inline-block" id="delete_table_all_reservations_' + data.id + '" method="post" action="delete_reservation/' + data.id + '">' +
                                window.csrf +
                                '<button style="width: 20px;height: 20px;display: inline-block;border: none;padding: 0;background: transparent;" title="' + window.langDialog.delete + '" class="btn btn-default btn-v3 show_reservation" id="delete_reservation_/' + data.id + '"><i class="fas fa-trash"></i></button>' +
                                '</form>';
                        }
                        return '';
                    }
                },
            ],
            language: {
                paginate: {
                    first: window.paginationLang.first,
                    previous: window.paginationLang.previous,
                    next: window.paginationLang.next,
                    last: window.paginationLang.last,
                },
                info: window.paginationLang.info,
                sLengthMenu: window.paginationLang.length_menu,
                search: window.langDialog.search
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
});
