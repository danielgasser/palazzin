/**
 * Created by pc-shooter on 17.12.14.
 */
var searchSortPaginate = function (url, searchStr, dateSearch, sortField, orderByField, paginate, callback, monthyear) {
    "use strict";
    var dummy = callback,
        my = (monthyear !== undefined || monthyear === '') ? monthyear : '';
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            search_field: searchStr,
            sort_field: sortField,
            order_by: orderByField,
            page: paginate,
            start: dateSearch,
            user_id: $('#user_id').val(),
            monthyear: my
        },
        success: function (d) {
            window.unAuthorized(d);
            dummy(d);
        }
    });
};

var urlTop = (window.thatRoute.indexOf('admin') > -1) ? '/admin/reservations/search' : '/user/reservations/search';
var fillKeeperTable = function (obj) {
    "use strict";
    var trStr = '';
    if (obj === undefined || obj.length === 0) {
        $('#keeperData').html('Keine Daten');
        return false;
    }
    $.each(obj, function (i, n) {
        trStr += '<tr id="reserv_' + n.id + '">' +
            '<td>' + n.user_first_name + '</td>' +
            '<td>' + n.user_name + '</td>' +
            '<td id="currentCalDate_' + n.reservation_started_at + '">' + n.reservation_started_at_show + '</td>' +
            '<td>' + n.reservation_ended_at_show + '</td>' +
            '<td>' + n.reservation_nights + '</td>' +
            '<td>';
        if (n.guests.length === 0) {
            trStr += window.langRes.guest_many_no_js.none;
        } else {
            trStr += '<table class="table" id="allGuests_{{$r->id}}">' +
                '<thead>' +
                '<tr>' +
                '<th>' + window.langRes.guests.number + '</th>' +
                '<th>' + window.langRes.guests.role + '</th>' +
                '<th>' + window.langRes.guests.tax_night + '</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';
            $.each(n.guests, function (j, m) {
                trStr += '<tr>' +
                    '<td>' + m.guest_number + '</td>' +
                    '<td>' + m.role_code + '</td>' +
                    '<td>' + m.role_tax_night + '</td>' +
                    '</tr>';
            });
            trStr += '</tbody>' +
                '</table>';
        }
        trStr += '</td>' +
            '</tr>';
    });
    $('#keeperData').html(trStr);
};

$(document).ready(function () {
    "use strict";
    var calstart = window.settings.setting_calendar_start.split(' ')[0],
        y = $('#year'),
        m = $('#month');
    window.yl = window.createYearList();
    window.ml = window.createMonthList();
    window.fillSelect(m, window.ml, false, '', true);
    window.fillSelect(y, window.yl, true, '', true);
    y.val('x');
    m.val('x');

    if (window.thatRoute !== 'user/reservations') {
        $('#searchIt').chosen({
            no_results_text: "Keine Einträge gefunden",
            enable_split_word_search: true,
            search_contains: true
        });
    }
});
$(document).on('click', '#resetUserDropDown', function(e) {
    "use strict";
    $('#searchIt').val('');
    $('#month').val(null);
    $('#year').val(null);
    window.location.reload();
});
$('#searchKeeper').on('click', function () {
    "use strict";
    var searchUser = $('#searchIt')[0].selectedOptions[0].value,
        y = ($('#year').val() !== 'x') ? $('#year').val() : null,
        m = ($('#month').val() !== 'x') ? $('#month').val() : null,
        dd,
        ddm;
    if (y === null) {
        ddm = '-' + window.smallerThenTen((parseInt(m, 10) + 1)) + '-';
    }
    if (m === null) {
        ddm = window.smallerThenTen((parseInt(y, 10))) + '-';
    }
    if (y !== null && m !== null) {
        dd = y + '-' + window.smallerThenTen((parseInt(m, 10) + 1)) + '-01';
    }
    if (y === null && m === null) {
        ddm = '';
    }
    searchSortPaginate(urlTop, searchUser, dd, 'user_name', 'DESC', '', fillKeeperTable, ddm);
});
$(window.a).tablesorter();

$(window.a).bind('sortStart', function (e) {
    "use strict";
    $('#keeperData').html('');
});
$(window.a).bind('sortEnd', function (e) {
    "use strict";
    var searchUser = $('#searchIt')[0].selectedOptions[0].value,
        sl = e.target.config.sortList[0],
        field = $($('.sortCols')[sl[0]]).attr('id'),
        sortby = (sl[1] === 1) ? 'ASC' : 'DESC',
        y = ($('#year').val() !== 'x') ? $('#year').val() : null,
        m = ($('#month').val() !== 'x') ? $('#month').val() : null,
        dd,
        ddm;
    if (y === null) {
        ddm = '-' + window.smallerThenTen((parseInt(m, 10) + 1)) + '-';
    }
    if (m === null) {
        ddm = window.smallerThenTen((parseInt(y, 10))) + '-';
    }
    if (y !== null && m !== null) {
        dd = y + '-' + window.smallerThenTen((parseInt(m, 10) + 1)) + '-01';
    }
    if (y === null && m === null) {
        ddm = '';
    }
    searchSortPaginate(urlTop, searchUser, dd, field, sortby, '', fillKeeperTable, ddm);
});
