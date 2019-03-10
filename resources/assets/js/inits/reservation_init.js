$(document).ready(function () {
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
                    data: 'reservation_bill_sent',
                    render: function (data, type, full, meta) {
                        return (data === 0) ? 'Nein' : 'Ja';
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
