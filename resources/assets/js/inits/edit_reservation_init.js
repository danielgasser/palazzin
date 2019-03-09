$(document).ready(function () {
    localStorage.clear();
    $('#clone_guest').attr('disabled', false);
    $('#reset_reservation').attr('disabled', false);
    localStorage.setItem('new_res', '0');
    let startDateStr = window.userPeriod.period_start.split(' '),
        startDateString = startDateStr[0].split('-'),
        endDateStr = window.userPeriod.period_end.split(' ');
    window.endDateString = endDateStr[0].split('-');
    window.startDate = new Date(startDateString[0], (startDateString[1] - 1), startDateString[2], 0, 0, 0);
    V3Reservation.initEdit(window.periodID, window.startDate);
})
