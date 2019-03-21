$(document).ready(function () {
    localStorage.clear();
    $('[id^="clone_guest_"]').attr('disabled', false);
    $('#reset_reservation').attr('disabled', false);
    localStorage.setItem('new_res', '0');
    let startDateStr = window.userPeriod.period_start.split(' '),
        startDateString = startDateStr[0].split('-'),
        endDateStr = window.userPeriod.period_end.split(' ');
    window.endDateString = endDateStr[0].split('-');
    window.startDate = new Date(startDateString[0], (startDateString[1] - 1), startDateString[2], 0, 0, 0);
    V3Reservation.initEdit(window.periodID, window.startDate);
    $('[id^="reservation_guest_guests_"]').attr('disabled', false);
    $('[id^="reservation_guest_num_"]').attr('disabled', false);
    if ($('[id^="guests_date_"]').length === 0) {
        $('#addZeroGuest').show();
    }
    $.each($('input:not([type="hidden"]), select'), function () {
        window.allInputs.push($(this).attr('id'));
    });

})
