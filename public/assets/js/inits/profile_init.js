/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";

    if ($('#error-wrap').length > 0) {
        var z;
        z = jQuery('.error-field').first().text();
        if (z.length > 0) {
            jQuery('.' + z).focus();
        }
    }

    jQuery(document).on('change', '#user_payment_method', function () {
        jQuery('#userProfile').submit();
    });

    jQuery(document).on('click', '[class^="user_fon"][class$="_label"]', function (e) {
        e.preventDefault();
        var id = $(this).attr('class').split('fon'),
            rid = id[1].split('_')[0];

        $('input[name="user_fon' + rid + '_label"]').val($(this).children('a').attr('href'));
        $('input[name="user_fon' + rid + '_label_show"]').val($(this).text());
    });
    $('#user_birthday').datepicker({
        format: "dd.mm.yyyy",
        weekStart: 1,
        todayBtn: "linked",
        clearBtn: true,
        language: 'de',
        calendarWeeks: true,
        autoclose: true,
    });

});
