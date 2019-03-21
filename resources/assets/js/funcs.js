let GlobalFunctions = {
    arraySearch: function (arr, val) {
        for (let i = 0; i < arr.length; i++)
            if (arr[i] === val) {
                return i;
            }
        return false;
    },
    getUserPeriod: function (p) {
        for (let i = 0; i < p.length; i++) {
            if (p[i].id === parseInt(window.periodID, 10)) {
                return p[i];
            }
        }
        return null;
    },
    superFilter: function (data, filter) {
        let arr = [];
        for (let i = 0; i < data.length; i++) {
            Object.keys(data[i]).filter(function(k) {
                if (k.indexOf(filter) === 0 && data[i][k] !== undefined) {
                    arr[k] = data[i][k];
                }
            });
        }
        return arr;
    },
    smallerThenTen: function (i, space) {
        "use strict";
        if (space) {
            return (i < 10) ? '&nbsp;&nbsp;' + i : i;
        }
        return (i < 10) ? '0' + i : i;
    },
    fillUserRoles: function (data, isJson) {
        var roleData = (isJson) ? data : $.parseJSON(data),
            dataStr = '',
            allRoles = [];
        if ($('#no_role').length > 0) {
            $('#no_role').hide();
        }
        dataStr += '<tr id="role_' + roleData.id + '">' +
            '<td id="deleteRole_' + roleData.id + '_' + roleData.role_c + '">' +
            '<input type="hidden" name="role_id[]" id="role_id_' + roleData.id + '" value="' + roleData.id + '">' +
            '<span class="btn btn-sm glyphicon glyphicon-remove" aria-hidden="true"></span></td>' +
            '<td>' +
            roleData.role_code +
            '<td>' + roleData.role_tax_annual + '</td>' +
            '<td>' + roleData.role_tax_night + '</td>' +
            '<td>' + roleData.role_tax_stock + '</td>' +
            '<td>' +
            '<ul>';
        $.each(roleData.role_rights, function (i, n) {
            dataStr += '<li>' + n + '</li>';
        });
        dataStr += '</ul>' +
            '</td></tr>';
        $('#role_id option[value="' + roleData.id + '"]').remove();
        if ($('#deleteRole_' + roleData.id + '_' + roleData.role_c).length > 0) {
            $('#roles').html(dataStr);
        } else {
            $('#roles').append(dataStr);
        }
        $.each($('[id^="deleteRole_"]'), function (i, n) {
            allRoles.push(n.id.split('_')[1]);
        });
        $('#role_id_add').val(allRoles.join(','));
    },
    showDate: function (d, format) {
        if (format === 'long') {
            return GlobalFunctions.smallerThenTen(d.getDate()) + '. ' + window.monthNames[d.getMonth()] + ' ' + d.getFullYear();
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
            return window.weekdayNames[d.getDay()] + ', ' + GlobalFunctions.smallerThenTen(d.getDate()) + '. ' + window.monthNames[d.getMonth()] + ' ' + d.getFullYear();
        }
        return GlobalFunctions.smallerThenTen(d.getDate()) + '. ' + GlobalFunctions.smallerThenTen((d.getMonth() + 1)) + '. ' + d.getFullYear();
    },
    scrollIt: function (sel, h, speed) {
        "use strict";
        $(sel).animate({scrollTop: h}, speed);
    },
    createYearList: function () {
        "use strict";
        var yearSelect = [],
            i = parseInt(window.settings.setting_calendar_start.split('-')[0], 10);
        while (i <= (parseInt(window.settings.setting_calendar_start.split('-')[0], 10) + parseInt(window.settings.setting_calendar_duration, 10))) {
            yearSelect.push(i);
            i += 1;
        }
        return yearSelect;
    },
    createMonthList: function () {
        "use strict";
        var monthSelect = [],
            i = 0,
            d = new Date();
        d.setMonth(i);
        d.setDate(1);
        while (i <= 11) {
            monthSelect.push(GlobalFunctions.showDate(d, 'month'));
            i += 1;
            d.setMonth(i);
        }
        return monthSelect;
    },
    fillSelect: function (el, opts, txtVal, color, all) {
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
    },
    unAuthorized: function (data) {
        if (data.length > 0) {
            if ($.parseJSON(data).hasOwnProperty('401_error')) {
                window.location.href = '/login';
            }
        }
    }
};
