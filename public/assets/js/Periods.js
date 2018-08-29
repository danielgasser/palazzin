/**
 * Created by pcshooter on 11.10.14.
 */
var putPeriodsToCalendar = function (pObj, el) {
    "use strict";
    var ps = [],
        endP = new Date(),
        startP = new Date();
    jQuery.each(pObj, function (i, n) {
        ps = n.period_start_js.split('_');
        startP.setFullYear(ps[0]);
        startP.setMonth(ps[1] - 1);
        startP.setDate(ps[2]);
        startP.setMinutes(0);
        startP.setSeconds(0);

        ps = n.period_end_js.split('_');
        endP.setFullYear(ps[0]);
        endP.setMonth(ps[1] - 1);
        endP.setDate(ps[2]);
        endP.setMinutes(0);
        endP.setSeconds(0);
        do {
            $(el + startP.getFullYear() + '_' + window.smallerThenTen(startP.getMonth() + 1) + '_' + window.smallerThenTen(startP.getDate())).addClass(n.clan_code);
            startP.setDate(startP.getDate() + 1);
        } while (startP.getTime() <= endP.getTime());
    });
};