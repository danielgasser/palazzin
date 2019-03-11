$(document).ready(function () {
    "use strict";

    if ($('#error-wrap').length > 0) {
        var z;
        z = $('.error-field').first().text();
        if (z.length > 0) {
            $('.' + z).focus();
        }
    }

    $(document).on('change', '#user_payment_method', function () {
        $('#userProfile').submit();
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
