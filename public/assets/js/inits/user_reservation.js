/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    jQuery(document).on('click', '[id^="reserv_"]', function () {
        var s = $(this).find('[id^="currentCalDate_"]').first().attr('id').split('_'),
            d = new Date(Date.UTC(s[1], (parseInt(s[2], 10) - 1), parseInt(s[3], 10)));
        d = new Date(s[1], (parseInt(s[2], 10) - 1), parseInt(s[3], 10));
        window.setCurrentCalendarDate(d);
        window.location.href = '/reservation';
    });
});
