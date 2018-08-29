/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    var v = [],
        timerEl = $('#loginDataContent'),
        loginUsers = window.loginUsers,
        errorEl = $('#error-no-login-data'),
        s,
        e;
    $('#total').html(window.countIt('.count'));
    $(window.loginUsers).timeLiner({
        showObject: '.timer',
        startDate: window.start,
        endDate: window.end
    });
    window.replaceTitleThreeParts({startTextDate: window.start, endTextDate: window.end}, '#titleStart', '#between', '#titleEnd', true);
    v.push(window.parseInt(window.settings.setting_calendar_start.split('-')[1], 10));
    v.push(window.parseInt(window.settings.setting_calendar_start.split('-')[0], 10));
    v.push(window.parseInt(window.settings.setting_calendar_start.split('-')[1], 10) + 1);
    v.push(window.parseInt(window.settings.setting_calendar_start.split('-')[0], 10));
    if (errorEl.length > 0) {
        errorEl.remove();
    }
    timerEl.children().show();
    if (loginUsers.monthBigger || loginUsers.yearBigger || loginUsers.length === undefined) {
        timerEl.children().hide();
        timerEl.prepend('<div id="error-no-login-data" class="error">' + window.loginUsers.error + '</div>');
        return false;
    }
    s = [0].startDate;
    e = [0].endDate;
    $(loginUsers).timeLiner({
        showObject: '.timer',
        startDate: s,
        endDate: e
    });
    replaceTitleThreeParts(loginUsers[0], '#titleStart', '#between', '#titleEnd', (s === e));
    $(document).on('change', 'select', function () {
        $.each($(':selected').toArray(), function () {
            v.push($(this).val());
        });
        window.searchLogins(v, window.admin_stats);
    });
});