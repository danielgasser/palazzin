$(document).ready(function () {
    localStorage.clear();
    localStorage.setItem('new_res', '1');
    if (afterValidation ==='1') {
        let startDateString = window.oldReservationStarted.split('.');
        startDate = new Date(startDateString[2], (startDateString[1] - 1), startDateString[0], 0, 0, 0);
        V3Reservation.initNew(window.oldPeriodID, window.startDate);
    } else {
        V3Reservation.initNew(window.periodID, new Date());
    }
    $('#reservation_guest_guests_0').attr('disabled', true);
    $('#reservation_guest_num_0').attr('disabled', true);
    $.each($('[id^="reservation_guest_ended_at_"]'), function (i, n) {
        $(n).addClass('noClick');
        $('#reservation_guest_num_' + i).addClass('noClick');
        $('#reservation_guest_started_at_' + i).addClass('noClick');
        $('#reservation_guest_guests_' + i).addClass('noClick');
    });

    $('#reservation_started_at').addClass('giveFocus');
    $.each($('input:not([type="hidden"]), select'), function () {
        window.allInputs.push($(this).attr('id'));
    });
});

