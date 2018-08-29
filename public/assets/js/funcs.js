/**
 * Created by pcshooter on 05.10.14.
 */
var setNavActive = function (el) {
    "use strict";
    var obj = $(el),
        url = window.location.pathname.split('/'),
        route = url.join('/');
    obj.removeClass('active');
    if (route === '/') {
        $('a[href="/"]').parent().addClass('active');
    }
    $('a[href$="' + route + '"]').parent().addClass('active');
};

var replaceTitleThreeParts = function (data, partOne, partTwo, partThree, hasThree) {
    "use strict";
    $(partOne).html(data.startTextDate);
    if (hasThree) {
        $(partThree).hide();
        $(partTwo).hide();
    } else {
        $(partThree).show();
        $(partTwo).show();
        $(partThree).html(data.endTextDate);
    }
};

var searchLogins = function (v, url) {
    "use strict";
    var endMonthChoice = $('#endMonthChoice'),
        endYearChoice = $('#endYearChoice'),
        startMonthChoice = $('#startMonthChoice'),
        startYearChoice = $('#startYearChoice'),
        loginUsers;

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            searchParams: v
        },
        success: function (data) {
            var timerEl = $('#loginDataContent'),
                errorEl = $('#error-no-login-data'),
                s,
                e;
            if (errorEl.length > 0) {
                errorEl.remove();
            }
            timerEl.children().show();
            if (data.monthBigger || data.yearBigger || data.length === undefined) {
                timerEl.children().hide();
                timerEl.prepend('<div id="error-no-login-data" class="error">' + data.error + '</div>');
                return false;
            }
            s = data[0].startDate;
            e = data[0].endDate;
            loginUsers = data;
            $(loginUsers).timeLiner({
                showObject: '.timer',
                startDate: s,
                endDate: e
            });
            replaceTitleThreeParts(data[0], '#titleStart', '#between', '#titleEnd', (s === e));
        }
    });
};
var getAllCookies = function (cname) {
    "use strict";
    var name = cname + '=',
        ca = document.cookie.split(';'),
        i,
        c;
    for (i = 0; i < ca.length; i += 1) {
        c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
};
var countIt = function (el) {
    "use strict";
    var total = 0;
    $.each($(el), function (i, n) {
        total += parseInt(n.innerHTML, 10);
    });
    return total;
};

var smallerThenTen = function (i) {
    "use strict";
    return (i < 10) ? '0' + i : i;
};


var getAppSettings = function (settingsUrl) {
    "use strict";
    $.ajax({
        type: 'GET',
        url: settingsUrl
    });

};

var getRoles = function (url, id) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            id: id
        },
        success: function (data) {
            var roleData = jQuery.parseJSON(data),
                dataStr = '',
                allRoles = [];
            if (jQuery('#no_role').length > 0) {
                jQuery('#no_role').hide();
            }
            dataStr += '<tr id="role_' + roleData.id + '">' +
                '<td id="deleteRole_' + roleData.id + '_' + roleData.role_c + '">' +
                '<span class="btn btn-sm glyphicon glyphicon-remove" aria-hidden="true"></span></td>' +
                '<td>' +
                roleData.role_code +
                '<td>' + roleData.role_tax_annual + '</td>' +
                '<td>' + roleData.role_tax_night + '</td>' +
                '<td>' + roleData.role_tax_stock + '</td>' +
                '<td>' +
                '<ul>';
            jQuery.each(roleData.role_rights, function (i, n) {
                dataStr += '<li>' + n + '</li>';
            });
            dataStr += '</ul>' +
                '</td></tr>';
            jQuery('#role_id option[value="' + roleData.id + '"]').remove();
            if ($('#deleteRole_' + roleData.id + '_' + roleData.role_c).length > 0) {
                jQuery('#roles').html(dataStr);
            } else {
                jQuery('#roles').append(dataStr);
            }
            jQuery.each(jQuery('[id^="deleteRole_"]'), function (i, n) {
                allRoles.push(n.id.split('_')[1]);
            });
            jQuery('#role_id_add').val(allRoles.join(','));
        }
    });

};

var roleFromToUser = function (url, rid, uid) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            role_id: rid,
            id: uid
        },
        success: function () {
            window.location.reload();
        }
    });

};

var deleteUser = function (url) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        success: function () {
            window.location.reload();
        }
    });

};

var rightFromRole = function (url, rightid, roleid) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            right_id: rightid,
            role_id: roleid
        },
        success: function (data) {
            window.location.reload();
        }
    });

};

var editRole = function (url, rid) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            role_id: rid
        },
        success: function () {
            window.location.reload();
        }
    });

};

var activateUser = function (url, activate, uid) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            userActive: activate,
            id: uid
        },
        success: function () {
            window.location.reload();
        }
    });
};

var changeClan = function (url, clan_id, uid) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            clan_id: clan_id,
            id: uid
        },
        success: function (data) {
            window.location.reload();
        }
    });
};

var getAuthUser = function () {
    "use strict";
    $.ajax({
        type: 'GET',
        url: 'reservation/getuser',
        async: false,
        success: function (data) {
            return data;
        }
    });
};

var adaptEmptyInputs = function () {
    "use strict";
    var old_id,
        lngFormat = window.df,
        dateSeparator = window.dfs,
        new_id,
        value_to_take,
        new_value;
    // datepicker overall
    //if (!window.Modernizr.inputtypes.date) {
        $.each($('.date_type'), function (i, n) {
            old_id = $(n).attr('id');
            $(n).datepicker({
            }, $.datepicker.regional['de']);

            $(n).datepicker('refresh');
        });
    //}

};
var adaptInputs = function (isGuest) {
    "use strict";
    var ss,
        lngFormat = window.df,
        dateSeparator = window.dfs,
        workDate,
        old_id,
        old_name,
        old_min,
        old_max,
        old_val,
        zzzzz = [],
        yyyyy,
        rrrrr,
        new_id,
        selector = (isGuest) ? '.date_type[name*="guest"]' : '.date_type',
        opts = {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric'
        };
    // datepicker overall
    //if (!window.Modernizr.inputtypes.date) {
        $.each($('input.date_type'), function (i, n) {
            if (n.value != '') {
                zzzzz.push(n.value);
            }
            //if (!$(n).hasClass('hasDatepicker')) {
                if ($(n).val().indexOf('-') > -1) {
                    dateSeparator = '-';
                }
                ss = $(n).val().split(dateSeparator);
                if (dateSeparator === '.') {
                    //workDate = new Date(Date.UTC(ss[2], (ss[1] - 1), ss[0], 0, 0, 0, 0));
                    workDate = new Date(ss[2], (ss[1] - 1), ss[0], 0, 0, 0, 0);
                } else {
                    //workDate = new Date(Date.UTC(ss[0], (ss[1] - 1), ss[2], 0, 0, 0, 0));
                    workDate = new Date(ss[0], (ss[1] - 1), ss[2], 0, 0, 0, 0);
                }
                old_id = $(n).attr('id');
                new_id = (old_id.indexOf('show') === -1) ? 'show_' + old_id : old_id;
                old_min = new Date(window.localStorage.getItem('periodStart'));
                old_max = new Date(window.localStorage.getItem('periodEnd'));
                //old_min = new Date($(n).attr('min'));
                //old_max = new Date($(n).attr('max'));
                old_val = $(n).val();
                $(n).attr('data_separator', dateSeparator);
                $(n).attr('name', new_id);

                workDate.setMinutes(workDate.getMinutes() - workDate.getTimezoneOffset());
                if ($(n).parent('div').children('.date-input').length === 0) {
                    if (old_id.indexOf('guest') > -1) {
                        old_name = old_id.split('_');
                        old_name = old_name[0] + '_' + old_name[1] + '_' + old_name[2] + '_' + old_name[3] + '[]';
                    } else {
                        old_name = old_id;
                    }
                   // $(n).parent('div').append('<input id="' + old_id + '" class="date-input" name="' + old_name + '" type="hidden" value="' + workDate.getFullYear() + '-' + window.smallerThenTen(window.parseInt(workDate.getMonth(), 10) + 1) + '-' + window.smallerThenTen(workDate.getDate()) + '" />');
                }
                if (old_id.indexOf('show_') === -1) {
                    $(n).attr('id', new_id);
                }

                if (zzzzz.length > 0) {
                    zzzzz = zzzzz[zzzzz.length - 1];
                }
                $('#' + new_id).datepicker({
                    beforeShow: function () {
                        zzzzz = $(this).attr('id').split('show_');
                        zzzzz = zzzzz[zzzzz.length - 1];
                    },
                    onClose: function () {
                        if ($(n).val().indexOf('.') > -1) {
                            dateSeparator = '.';
                        }
                        yyyyy = $(this).val().split(dateSeparator);
                        if (dateSeparator === '.') {
                            rrrrr = yyyyy[2] + '-' + yyyyy[1] + '-' + yyyyy[0];
                        } else {
                            rrrrr = yyyyy[0] + '-' + yyyyy[1] + '-' + yyyyy[2];
                        }
                        $('#' + zzzzz).val(rrrrr);
                        $('#' + zzzzz).trigger('change');
                    },
                    defaultDate: workDate,
                    setDate: workDate,
                    dateFormat: lngFormat,
                    altFormat: 'yy-mm-dd',
                    altField: '[name="' + old_id + '"]',
                    minDate: old_min,
                    maxDate: old_max
                }, $.datepicker.regional['de']);

                $('#' + new_id).datepicker('refresh');
                $('#' + new_id).val(showDate(workDate, ''));
            //}
            $('#' + new_id).val(showDate(workDate, ''));
        });
    //}
};
var showDate = function (d, format) {
    if (format === 'long') {
        return window.smallerThenTen(d.getDate()) + '. ' + window.monthNames[d.getMonth()] + ' ' + d.getFullYear();
    }
    if (format === 'short') {
        return window.monthNames[d.getMonth()] + ' ' + d.getFullYear();
    }
    if (format === 'nozero') {
        return d.getDate() + '. ' + (d.getMonth() + 1) + '. ' + d.getFullYear();
    }
    if (format === 'month') {
        return window.monthNames[d.getMonth()];
    }
    if (format === 'weekday') {
        return window.weekdayNames[d.getDay()] + ', ' + window.smallerThenTen(d.getDate()) + '. ' + window.monthNames[d.getMonth()] + ' ' + d.getFullYear();
    }
    return window.smallerThenTen(d.getDate()) + '. ' + window.smallerThenTen((d.getMonth() + 1)) + '. ' + d.getFullYear();
};
var scrollIt = function (sel, h, speed) {
    "use strict";
    $(sel).animate({scrollTop: h}, speed);
};
var disableDateInputsBeforeToday = function (excluded_class) {
    "use strict";
    var today = new Date(),
        s,
        t,
        u,
        inputDate;
    if (excluded_class === undefined) {
        u = '';
    } else {
        u = excluded_class;
    }
    today.setHours(0);
    today.setMinutes(0);
    today.setSeconds(0);
    today.setMilliseconds(0);
    $.each($('input[type=date]'), function (i, n) {
        if (n.value !== '') {
            s = n.value.split('-');
            t = s[0] + '-' + s[1] + '-' + s[2];
            //inputDate = new Date(Date.UTC(s[0], (s[1] - 1), s[2], 0, 0, 0, 0));
            inputDate = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0);
            if (today.getTime() > inputDate.getTime() || $(n).hasClass(u)) {
                $(n).attr('readonly', true);
            } else {
                $(n).removeAttr('readonly');
            }
        }
    });
};

var disableInputsBeforeToday = function (d, fields) {
    "use strict";
    var today = new Date();
    today.setHours(0);
    today.setMinutes(0);
    today.setSeconds(0);
    today.setMilliseconds(0);
    $.each(fields, function (i, n) {
        if (today.getTime() > d.getTime()) {
            $(n).attr('readonly', true);
        }
    });
};

var isYesterDay = function (d) {
    "use strict";
    var today = new Date();
    today.setHours(0);
    today.setMinutes(0);
    today.setSeconds(0);
    today.setMilliseconds(0);
    return (today.getTime() > d.getTime());
};
var invalidInputMessages = function (input, msgId) {
    "use strict";
    input.oninvalid = function (e) {
        e.target.setCustomValidity("");
        if (!e.target.validity.valid) {
            e.target.setCustomValidity(window.langRes.warnings[msgId]);
        }
    };
};

var toggleStuff = function (el) {
    "use strict";
    var height;
    if (el.css('display') === 'block') {
        height = 0;
    } else {
        height = '+=' + el.height();
    }
    $('#main-nav').slideToggle(50);
    el.slideToggle(50);
    $('#wrap').animate({
        top: -($('#editReservMenu').height())
    }, 50);
    $('body, html').animate({ scrollTop: 0 }, 50);
    window.setTimeout(function () {
        if (el.css('display') === 'block') {
            document
                .getElementById('calendar')
                .style.pointerEvents = 'none';
        } else {
            document
                .getElementById('calendar')
                .style.pointerEvents = 'auto';
        }
    }, 60);
};

var setCurrentCalendarDate = function (d) {
    "use strict";
    window.localStorage.setItem('currentCalendarDate', d.getTime());
    //adaptInputs(false);
};

var getcurrentCalendarDate = function () {
    "use strict";
    return window.localStorage.getItem('currentCalendarDate');
};
var setCalendarDayProfileText = function () {
    "use strict";
    if (window.localStorage.hasOwnProperty('guessWhoWidth')) {
        var t = window.localStorage.getItem('guessWhoWidth'),
            tt = $(t + '>span.guess_me>a').text(),
            ttt = tt.substr(0, 10);

        $(t + '>span.guess_me>a').text(ttt + '...');
    }
};
var putCalendarDateToSession = function (d) {
    "use strict";
    var url,
        tstamp = new Date(parseInt(window.localStorage.getItem('currentCalendarDate'), 10)),
        tnow = parseInt(d.getTime() / 1000, 10);
    if (window.localStorage.hasOwnProperty('currentCalendarDate') && (window.localStorage.getItem('currentCalendarDate') !== '' || window.localStorage.getItem('currentCalendarDate') !== null)) {
        url = 'reservation/savecaldate/' + parseInt(tstamp.getTime() / 1000, 10) + '/';
    } else {
        url = 'reservation/savecaldate/' + tnow;
    }
    $.ajax({
        type: 'GET',
        url: url
    });
};

var putUserSearchResultsToSession = function (url, html_d) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            html_data: html_d
        }
    });
};
var createYearList = function () {
    "use strict";
    var yearSelect = [],
        i = parseInt(window.settings.setting_calendar_start.split('-')[0], 10);
    while (i <= (parseInt(window.settings.setting_calendar_start.split('-')[0], 10) + parseInt(window.settings.setting_calendar_duration, 10))) {
        yearSelect.push(i);
        i += 1;
    }
    return yearSelect;
};
var createMonthList = function () {
    "use strict";
    var monthSelect = [],
        i = 0,
        d = new Date();
    d.setMonth(i);
    d.setDate(1);
    while (i <= 11) {
        monthSelect.push(showDate(d, 'month'));
        i += 1;
        d.setMonth(i);
    }
    return monthSelect;
};

var fillSelect = function (el, opts, txtVal, color, all) {
    "use strict";
    var c = (color !== undefined) ? ' style="color: ' + color + '"' : '',
        putAll = (all !== undefined && all) ? {val: 'x', text: window.langDialog.all} : {};
    $.each(opts, function (val, text) {
        if (txtVal) {
            el.append(
                $('<option></option>').val(text).html(text)
            );
        } else {
            el.append(
                $('<option' + c + '></option>').val(val).html(text)
            );
        }
    });
    if ((all !== undefined && all)) {
        el.prepend($('<option' + c + '></option>').val(putAll.val).html(putAll.text));
    }
};

/**
 *
 * @param url
 * @param searchStr
 * @param dateSearch
 * @param sortField
 * @param orderByField
 * @param paginate
 * @param callback
 */
var searchSortPaginate = function (url, searchStr, dateSearch, sortField, orderByField, paginate, callback) {
    "use strict";
    var dummy = callback;
    $.ajax({
        type: 'GET',
        url: url,
        dataType:'text',
        contentType: 'application/x-www-form-urlencoded;charset=utf-8',
        data: {
            search_field: searchStr,
            sort_field: sortField,
            order_by: orderByField,
            page: paginate,
            start: dateSearch,
            user_id: $('#user_id').val()
        },
        success: function (d) {
            //window.location.reload();
           dummy(d);
        }
    });
};


var fillKeeperTable = function (obj) {
    "use strict";
    var trStr = '';
    if (obj === undefined || obj.length === 0) {
        $('#keeperData').html('Keine Daten');
        return false;
    }
    $.each(obj, function (i, n) {
        trStr += '<tr>' +
            '<td>' + n.user_first_name + '</td>' +
            '<td>' + n.user_name + '</td>' +
            '<td>' + n.reservation_started_at_show + '</td>' +
            '<td>' + n.reservation_ended_at_show + '</td>' +
            '<td>' + n.reservation_nights + '</td>' +
            '<td>';
        $.each(n.guests, function (j, m) {
            trStr += m.guest_started_at_show + ' - ' + m.guest_ended_at_show + ': ' + m.guest_number + '<br>';
        });
            //    '01.01.2015 - 31.01.2015: 4<br>' +
        trStr += '</td>' +
            '</tr>';
    });
    $('#keeperData').html(trStr);
};
var fillBillTable = function (obj) {
    "use strict";
    var hrStr = '',
        trStr = '',
        paidForm;
    $('[id^="heading_"]').html('');
    $('[id^="keeperData_"]').html('');
    if (obj === undefined || obj.length === 0) {
        $('.white-row').html('');
        $('[id^="heading_"]').first().html('<h3>' + window.langError + '</h3>');
        return false;
    }
    if (window.route === 'admin/bills') {
        paidForm = window.billPaid;
    } else {
        paidForm = '';
    }
    $.each(obj, function (i, n) {
        var paid;
        if (n.bill_due === '1') {
            paid = window.langDialog.n;
        } else {
            paid = window.langDialog.y;
            paidForm = n.bill_paid_show;
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
                n.user_first_name + ' ' + n.user_name + '<br>' +
                n.user_address + '<br>' +
                n.user_zip + ' ' + n.user_city + '<br>' +
                n.country +
                '</th>' +
                '<th colspan="2" class="address">' +
                '<a href="mailto:' + n.email + '">' + n.email + '</a><br>' +
                n.user_fon1_label + ' ' + n.user_fon1 + '<br>' +
                n.user_fon2_label + ' ' + n.user_fon2 + '<br>' +
                n.user_fon3_label + ' ' + n.user_fon3 + '<br>' +
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
            '<th class="white-row">' + paid + '</th>' +
            '<th colspan="2" class="white-row" id="bill_paid">' +
            paidForm +
            '</th>' +
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
        trStr = '';
    });
    $('#bill_currency').html(obj[obj.length - 1].bill_currency);
    $('#bill_subtotals').html(obj[obj.length - 1].subtotals);
    $('#bill_taxes').html(obj[obj.length - 1].bill_tax + '%');
    $('#bill_totals').html(obj[obj.length - 1].totals);
};
var fillUserTable = function (obj) {
    "use strict";
    var hrStr = '',
        trStr = '',
        db,
        tbodyel = '#table-body',
        tb =  $('#users').outerWidth();
    if (obj === undefined || obj.length === 0) {
        $('#keeperData').html('Keine Daten');
        return false;
    }
    $('#records_no').html(obj.length);
    $(tbodyel).html('');
    $.each(obj, function (i, n) {
        var address = (n.user_address === undefined || n.user_address === '') ? ' - ' :  n.user_address,
            zip = (n.user_zip === undefined || n.user_zip === '') ? ' - ' :  n.user_zip,
            city = (n.user_city === undefined || n.user_city === '') ? ' - ' :  n.user_city,
            country = (n.user_country_name === undefined || n.user_country_name === '') ? ' - ' :  n.user_country_name,
            userNew = (n.user_new === '0') ? 'registriert' : 'neu',
            fonlabelOne = (window.langUser.fonlabel[n.user_fon1_label] === undefined || window.langUser.fonlabel[n.user_fon1_label] === '') ? ' - ' : window.langUser.fonlabel[n.user_fon1_label];
        trStr += '<tr class="tr-body">' +
            '<td><a href="https://palazzin.ch/user/profile/' + n.id + '">' +
            '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a></td>';
        if (window.location.href.indexOf('admin/users') > -1) {
            trStr += '<td><a href="' + window.urlTo + '/admin/users/edit/' + n.id + '">' +
                '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>';
            trStr += '<td>' +
                '<a id="destroyUser_' + n.id + '_' + n.user_first_name + '_' + n.user_name + '" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>' +
                '</td>' +
                '<td>' + userNew + '</td>';
        }
        trStr += '<td>' + n.clan_description + '/<br> ';
        if (n.family_code !== undefined) {
            trStr += n.family_description;
        }
        trStr += ' </td>' +
            '<td class="firstname_1">' + n.user_first_name + '</td>' +
            '<td class="name_1">' + n.user_name + '</td>' +
            '<td>' + n.user_login_name + '</td>' +
            '<td>' +
            '<table class="table mailz">' +
            '<tbody>' +
            '<tr>' +
            '<td>' +
            '<a href="mailto:' + n.email + '">' + n.email + '</a>' +
            '</td>' +
            '</tr>';
        if (n.user_email2 !== '' && n.user_email2 !== null) {
            trStr += '<tr>' +
                '<td>' +
                '<a href="mailto:' + n.user_email2 + '">' + n.user_email2 + '</a>' +
                '</td>' +
                '</tr>';
        }
        trStr += '</tbody>' +
            '</table>' +
            '</td>';
        if ((n.user_www !== '' && n.user_www !== null) && (n.user_www_label !== '' && n.user_www_label !== null)) {
            trStr += '<td><a href="https://' + n.user_www + '" target="_blank">' + n.user_www_label + '</a></td>';
        } else {
            trStr += '<td><a href="#"></a></td>';
        }
        trStr += '<td>' + address + '</td>' +
            '<td>' + zip + '</td>' +
            '<td>' + city + '</td>' +
            '<td>' + country + '</td>' +
            '<td>' +
            '<table class="table fonz">' +
            '<tbody>';
        trStr += '<tr>' +
            '<td>' + fonlabelOne + '<br>' + n.user_fon1 + '</td>' +
            '</tr>';
        if (n.user_fon2 !== '' && n.user_fon2 !== null) {
            trStr += '<tr>' +
                '<td>' + window.langUser.fonlabel[n.user_fon2_label] + '<br>' + n.user_fon2 + '</td>' +
                '</tr>';
        }
        if (n.user_fon3 !== '' && n.user_fon3 !== null) {
            trStr += '<tr>' +
                '<td>' + window.langUser.fonlabel[n.user_fon3_label] + '<br>' + n.user_fon3 + '</td>' +
                '</tr>';
        }
        trStr += '</tbody>' +
            '</table>' +
            '</td>';
        db = new Date(n.user_birthday  * 1000);
        trStr += '<td class="date-header">' + showDate(db, '') + '</td>' +
            '<td class="date-header">' + n.updated_at + '</td>' +
            '<td>' +
            '<table class="table">' +
            '<tbody>';
        if (n.roles !== undefined) {
            $.each(n.roles, function (i, m) {
                trStr += '<tr>' +
                    '<td>' +
                    window.langRole[m.role_code] +
                    '</td>' +
                    '</tr>';
            });
        }
        trStr += '</tbody>' +
            '</table>' +
            '</td>' +
            '</tr>';
    });
    $(tbodyel).html(trStr);
    jQuery('#users').TableWizard({
        tableWidth: $('.table-head>tr').outerWidth(),
        subTableWidth: $('.mailz>tbody>tr').innerWidth(),
        isAjax: true
    });

};
var getSession = function () {
};
