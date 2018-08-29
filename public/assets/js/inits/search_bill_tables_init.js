/**
 * Created by pc-shooter on 17.12.14.
 */
var fillBillTable = function (obj) {
    "use strict";
    var hrStr = '',
        trStr = '',
        paidForm = '',
        subTotals = 0.0,
        totalTotals = 0.0;
    $('[id^="heading_"]').html('');
    $('[id^="keeperData_"]').html('');
    $('#total_search').html(obj.length);
    if (obj === undefined || obj.length === 0) {
        $('.white-row').html('');
        $('.white-row').first().html('<h3>' + window.langError + '</h3>');
       // $('[id^="heading_"]').first().html('<h3>' + window.langError + '</h3>');
        return false;
    }
    $.each(obj, function (i, n) {
        var paid;
        if (n.bill_due === 1) {
            paid = window.langDialog.n;
            if (window.route === 'admin/bills') {
                paidForm = '<input class="form-control type_date date_type" id="bill_paid_' + n.id + '" data_id="' + n.id + '" name="bill_paid" type="text" readonly="readonly">' +
                    '<button class="btn btn-default" id="savePaid_' + n.id + '">' + window.langDialog.save + '</button>';
            } else {
                paidForm = '';
            }
        } else {
            paid = window.langDialog.y;
        }
        hrStr += '<thead id="heading_' + n.id + '">';
        hrStr += '<tr class="keeperDataHead">' +
            '<th style="height: 62px" class="white-row" colspan="5">' + window.langBill.bill_no.replace(/\<br\>/, ' - ') + ' ' + n.bill_no + '</th>' +
            '</tr>';
        hrStr += '<tr>' +
            '<th colspan="4">';
        if (window.route === 'admin/bills') {
            hrStr += window.langProfile.address;
        }
        hrStr += '</th>' +
            '<th>' +
            window.langBill.date +
            '</th>' +
            '</tr>';
        hrStr += '<tr>';
        if (window.route === 'admin/bills') {
            hrStr += '<th colspan="2" class="address">' +
                n.user.user_first_name + ' ' + n.user.user_name + '<br>' +
                n.user.user_address + '<br>' +
                n.user.user_zip + ' ' + n.user.user_city + '<br>' +
                n.user.country +
                '</th>' +
                '<th colspan="2" class="address">' +
                '<a href="mailto:' + n.user.email + '">' + n.user.email + '</a><br>' +
                n.user.user_fon1_label + ' ' + n.user.user_fon1 + '<br>' +
                n.user.user_fon2_label + ' ' + n.user.user_fon2 + '<br>' +
                n.user.user_fon3_label + ' ' + n.user.user_fon3 + '<br>' +
                '</th>';
        } else {
            hrStr += '<th colspan="4" class="address"></th>';
        }
        hrStr += '<th class="white-row address" colspan="1">' + n.bill_bill_date_show + '</th></tr>';


        hrStr += '<tr class="keeperDataHead">' +
            '<th>' + window.langRes.arrival + '</th>' +
            '<th>' + window.langRes.depart + '</th>' +
            '<th>' + window.langRes.nights + '</th>' +
            '<th>' + window.langRes.guests.title + '</th>' +
            '<th>PDF</th>' +
            '</tr>' +
            '</thead>';
        $('#allReservations').append(hrStr);
        hrStr = '';
        trStr += '<tbody id="keeperData_' + n.id + '">';
        trStr += '<tr>' +
            '<td>' + n.reservation.reservation_started_at_show + '</td>' +
            '<td>' + n.reservation.reservation_ended_at_show + '</td>' +
            '<td>' + n.reservation.reservation_nights + '</td>' +
            '<td>' +
            '<table class="table" id="allGuests_27">' +
            '<thead>';
        trStr += '<tr>' +
            '<th>' + window.langRes.arrival + '</th>'  +
            '<th>' + window.langRes.depart + '</th>' +
            '<th>' + window.langRes.guests.number + '</th>'  +
            '<th>' + window.langRes.guests.role + '</th>' +
            '<th>' + window.langRes.guests.tax_night + '</th>' +
            '<th>' + window.langRes.nights + '</th>' +
            '<th>' + window.langRes.guests.total + '</th>' +
            '</tr>' +
            '</thead>';
        trStr += '<tbody>';
        $.each(n.reservation.guests, function (i, n) {
            trStr += '<tr>' +
                '<td>' + n.guest_started_at_show + '</td>' +
                '<td>' + n.guest_ended_at_show + '</td>' +
                '<td>' + n.guest_number + '</td>' +
                '<td>' + n.role_code + '</td>' +
                '<td>' + n.role_tax_night + '</td>' +
                '<td>' + n.guest_night + '</td>' +
                '<td>' + n.guestSum.toFixed(2) + '</td>' +
                '</tr>';
        });
        trStr += '</tbody>' +
            '</table>' +
            '</td>' +
            '<td>' +
            '<a target="_blank" href="' + window.urlTo + '/' + n.bill_path + '">' + n.bill_no + '.pdf</a>' +
            '</td>' +
            '</tr>' +
            '<tr>' +
            '<td colspan="5">' +
            '<table id="bill_totals" style="width: 100%;">' +
            '<thead>' +
            '<tr>' +
            '<th class="white-row">' + window.langBill.currency + '</th>' +
            '<th class="white-row">' + window.langBill.sub_total_bill + '</th>' +
            '<th class="white-row">' + window.langBill.taxes + '</th>' +
            '<th class="white-row">' + window.langBill.total_bill + '</th>' +
            '<th class="white-row">' + window.langBill.paid + '</th>' +
            '<th colspan="2" class="white-row">' + window.langBill.paid_at + '</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '<tr>' +
            '<th class="white-row">' + n.bill_currency + '</th>' +
            '<th class="white-row">' + n.bill_sub_total + '</th>' +
            '<th class="white-row">' + n.bill_tax + '%</th>' +
            '<th class="white-row">' + n.bill_total + '</th>' +
            '<th class="white-row" id="paid_or_not_' + n.id + '">' + window.pay_yesno[n.bill_due] + '</th>';
        if (n.bill_due === 0) {
            trStr += '<th colspan="2" class="white-row" id="when_paid_' + n.id + '">' + n.bill_paid_show ;
            if (window.route === 'admin/bills') {
                trStr += '<br><button class="btn btn-default" id="undoSavePaid_' + n.id + '">' + window.langDialog.reset + '</button>';
            }
        } else {
            trStr += '<th colspan="2" class="white-row" id="when_paid_' + n.id + '">' +
                paidForm +
                '</th>';
        }
        trStr +=
            '</tr>' +
            '<tr>' +
            '<th colspan="6" style="border-top: 4px double #000000;"></th>' +
            '</tr>' +
            '</tbody>' +
            '</table>' +
            '</td>' +
            '</tr>' +
            '</tbody>';
        $('#allReservations').append(trStr);
        $('#bill_paid').attr('data-id', n.id);
        trStr = '';
    });
    subTotals = obj[obj.length - 1].subtotals;

    $('#bill_currency').html(obj[obj.length - 1].bill_currency);
    $('#bill_subtotals').html(subTotals);
    $('#bill_taxes').html(obj[obj.length - 1].bill_tax + '%');
    $('#bill_totals').html(obj[obj.length - 1].totals);
    window.adaptEmptyInputs();

};
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
            paid: $('#search_bill_paid').val(),
            monthyear: my
        },
        success: function (d) {
            console.log(d)
            dummy(d);
        }
    });
};
var searchBillNo = function (billNo, callback) {
    "use strict";
    var dummy = callback;
    $.ajax({
        type: 'GET',
        url: '/bill/search_billno',
        data: {
            bill_no: billNo
        },
        success: function (d) {
            dummy(d);
        }
    });
};

$(document).ready(function () {
    "use strict";
    var d = new Date(),
        y = $('#year'),
        m = $('#month');
    window.yl = window.createYearList();
    window.ml = window.createMonthList();
    window.fillSelect(m, window.ml, false, '', true);
    window.fillSelect(y, window.yl, true, '', true);
    y.val('x');
    m.val('x');
    $("#billNo").val('No-');
    if (window.route !== 'user/bills') {
        $('#searchIt').chosen({
            no_results_text: "Keine Einträge gefunden",
            enable_split_word_search: true,
            search_contains: true
        });
    }
});
$(document).on('click', '#resetUserDropDown', function (e) {
    "use strict";
    $('#searchIt').val('').trigger("chosen:updated").trigger('change');
});
$(document).on('click', '#resetKeeper', function (e) {
    "use strict";
    $('#searchIt').val('');
    $('#month').val('');
    $('#year').val('');
    window.location.reload();
});
/*
select2.onSelect = function () {
    "use strict";
    $('#month').val('');
    $('#year').val('');
    searchSortPaginate('/bill/search', $(this).val(), '', 'bill_bill_date', 'ASC', $('#search_bill_paid').val(), fillBillTable);
};
 */
$(document).on('keyup', '#billNo', function (e) {
    "use strict";
    e.preventDefault();
    var bill_no = $(this).val(),
        no = 'No-';
    if (bill_no.length < 3) {
        return false;
    }
    if (bill_no.indexOf(no) === -1) {
        $(this).val(no + bill_no);
        bill_no = no + bill_no;
    }
    searchBillNo(bill_no, fillBillTable);
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
    searchSortPaginate('/bill/search', searchUser, dd, 'bill_bill_date', 'DESC', $('#search_bill_paid').val(), fillBillTable, ddm);
});

$('#searchUserKeeper').on('click', function () {
    "use strict";
    var searchUser = $('#searchIt').text(),
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
    searchSortPaginate('/bill/search', searchUser, dd, 'bill_bill_date', 'DESC', $('#search_bill_paid').val(), fillBillTable, ddm);
});

$(window.a).tablesorter();
$(window.a).bind('sortStart', function (e) {
    "use strict";
    $('#keeperData').html('');
});
$(window.a).bind('sortEnd', function (e) {
    "use strict";
    var sl = e.target.config.sortList[0],
        field = $(window.cols[sl[0]]).attr('id'),
        sortby = (sl[1] === 1) ? 'ASC' : 'DESC';
    window.searchSortPaginate('/bill/search/', $('#searchIt')[0].selectedOptions[0].value, field, sortby, null, window.fillBillTable);
});
