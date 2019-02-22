/**
 * Created by pc-shooter on 15.12.14.
 */
var nonHtml5InputsSet = false;
var isDate = function (first, last, obj) {
        "use strict";
        var fs = $(first)[0],
            ls = $(last)[0];
        if (obj.value === '' || obj.value === undefined) {
            if (obj.id.indexOf('started') > -1) {
                obj.value = $(fs).attr('data_year') + '-' + window.smallerThenTen((parseInt($(fs).attr('data_month'), 10) + 1)) + '-' + $(fs).attr('data_date');
            } else {
                obj.value = $(ls).attr('data_year') + '-' + window.smallerThenTen((parseInt($(ls).attr('data_month'), 10) + 1)) + '-' + $(ls).attr('data_date');
            }
            return false;
        }
        return true;
    },
    counterCalcCollection = function (start, end, sid) {
        "use strict";
        window.Reservation.nightCounter(start, end, sid);
        window.Reservation.guestCounter();
        window.Reservation.calcGuestsTotals(sid);
        window.Reservation.freeBedChecker(start, end, sid);
        window.Reservation.calcReservationTotals();
    };

jQuery(document).ready(function () {
    "use strict";

    jQuery(document).on('change', '#show_reservation_started_at, #show_reservation_ended_at', function () {
        var resStartEl = $('#show_reservation_started_at'),
            resEndEl = $('#show_reservation_ended_at'),
            separatorStart = (resStartEl.val().indexOf('-') > -1) ? '-' : '.',
            separatorEnd = (resEndEl.val().indexOf('-') > -1) ? '-' : '.',
            startArr = resStartEl.val().split(separatorStart),
            endArr = resEndEl.val().split(separatorEnd),
            id = $(this).attr('id'),
            startSave = startArr[2] + '_' + window.smallerThenTen(window.parseInt(startArr[1], 10) - 1) + '_' + startArr[0],
            endSave = endArr[2] + '_' + window.smallerThenTen(window.parseInt(endArr[1], 10) - 1) + '_' + endArr[0],
            start = new Date(Date.UTC(startArr[2], (startArr[1] - 1), startArr[0], 0, 0, 0, 0)),
            end  =  new Date(Date.UTC(endArr[2], (endArr[1] - 1), endArr[0], 0, 0, 0, 0));
        start = new Date(startArr[2], (startArr[1] - 1), startArr[0], 0, 0, 0, 0);
        end  =  new Date(endArr[2], (endArr[1] - 1), endArr[0], 0, 0, 0, 0);
        window.Reservation.checkNegativeDates(start, end);
       // $(id).datepicker('option', 'dd.mm.yyyy');
       // $(id).datepicker('refresh');
        $('#saveEditReserv').attr('disabled', false);
        window.Reservation.nightCounter(start, end);
        $.each($('[name^="show_reservation_guest_started_at"]'), function (i, n) {
            var d = $(n).attr('id').split('_'),
                showid = '#' + $(n).attr('id'),
                numid = '#reservation_guest_guests_' + d[5],
                id = d[d.length - 1],
                e = $(n).val().split('.'),
                a = resStartEl.val().split('.'),
                guestEnd = $('#show_reservation_guest_ended_at_' + i).val().split('.'),
                newStart = new Date(a[2], (parseInt(a[1]) - 1), parseInt(a[0])),
                oldStart = new Date(e[2], (parseInt(e[1]) - 1), parseInt(e[0])),
                guestEndDate = new Date(guestEnd[2], (parseInt(guestEnd[1], 10) - 1), parseInt(guestEnd[0], 10));
            if ($(numid).val() === '0') {
                $('#saveEditReserv').attr('disabled', true);
            }
            if (newStart.getTime() > oldStart.getTime()) {
                $(n).attr('min', resStartEl.val())
                    .attr('max', resEndEl.val());
                $(n).val(resStartEl.val());
                // datepicker overall
                $(showid).datepicker('setDate', resStartEl.val());
                $(showid).datepicker('option', 'minDate', newStart);
                $(showid).datepicker('option', 'maxDate', end);
                window.Reservation.nightCounter(newStart, guestEndDate, id);
            } else {
                $(showid).datepicker('option', 'minDate', start);
                $(showid).datepicker('option', 'maxDate', end);
            }
            $(showid).datepicker('refresh');
        });
        $.each($('[name^="show_reservation_guest_ended_at"]'), function (i, n) {
            var d = $(n).attr('id').split('_'),
                showid = '#' + $(n).attr('id'),
                id = d[d.length - 1],
                e = $(n).val().split('.'),
                a = $('#show_reservation_ended_at').val().split('.'),
                guestStart = $('#show_reservation_guest_started_at_' + i).val().split('.'),
                newEnd = new Date(a[2], (parseInt(a[1]) - 1), parseInt(a[0])),
                oldEnd = new Date(e[2], (parseInt(e[1]) - 1), parseInt(e[0])),
                guestStartDate = new Date(guestStart[2], (parseInt(guestStart[1], 10) - 1), parseInt(guestStart[0], 10));
            if (newEnd.getTime() < oldEnd.getTime()) {
                $(n).attr('min', guestStart)
                    .attr('max', newEnd);
                $(n).val(resEndEl.val());
                // datepicker overall
                $(showid).datepicker('setDate', $(n).val());
                $(showid).datepicker('option', 'minDate', start);
                $(showid).datepicker('option', 'maxDate', newEnd);
                window.Reservation.nightCounter(guestStartDate, newEnd, id);
            } else {
                $(showid).datepicker('option', 'minDate', start);
                $(showid).datepicker('option', 'maxDate', end);
            }
            $(showid).datepicker('refresh');
            window.Reservation.calcGuestsTotals(id, $('#reservation_guest_role_tax_night_' + id).val());
        });
        window.localStorage.setItem('startDate', startSave);
        window.localStorage.setItem('endDate', endSave);
        window.Reservation.calcReservationTotals();
        window.Reservation.addClickedDays(null);
    });
    jQuery(document).on('change', '[id^="show_reservation_guest_started_at_"], [id^="show_reservation_guest_ended_at_"]', function () {
        var id = $(this).attr('id').split('_'),
            sid = id[id.length - 1],
            s,
            e,
            start,
            end;
        // datepicker overall
        s = $('#show_reservation_guest_started_at_' + sid).val().split('.');
        e = $('#show_reservation_guest_ended_at_' + sid).val().split('.');
        // datepicker overall
        //start = new Date(Date.UTC(s[2], (s[1] - 1), s[0], 0, 0, 0, 0));
        //end  =  new Date(Date.UTC(e[2], (e[1] - 1), e[0], 0, 0, 0, 0));
        start = new Date(s[2], (s[1] - 1), s[0], 0, 0, 0, 0);
        end  =  new Date(e[2], (e[1] - 1), e[0], 0, 0, 0, 0);
        $('#reservation_guest_started_at_' + sid).val(s[2] + '-' + s[1] + '-' + s[0]);
        $('#reservation_guest_ended_at_' + sid).val(e[2] + '-' + e[1] + '-' + e[0]);
        start.setHours(1);
        window.Reservation.checkNegativeDates(start, end);
        $('#saveEditReserv').attr('disabled', false);
        counterCalcCollection(start, end, sid);
    });
    jQuery(document).on('input propertychange paste', '[name="reservation_guest_sum_num"]', function () {
        console.log(this.value)
    });
    jQuery(document).on('change mouseup', '[id^="reservation_guest_num_"]', function () {
        if (isNaN(parseInt(this.value, 10)) || this.value === '') {
            $('#guest_nan').show();
            return false;
        }

        var id = $(this).attr('id').split('_'),
            mid = id[id.length - 1],
            s,
            e,
            start,
            end;
        s = $('#show_reservation_guest_started_at_' + mid).val().split('.');
        e = $('#show_reservation_guest_ended_at_' + mid).val().split('.');
        //start = new Date(Date.UTC(s[2], (s[1] - 1), s[0], 0, 0, 0, 0));
        //end  =  new Date(Date.UTC(e[2], (e[1] - 1), e[0], 0, 0, 0, 0));
        start = new Date(s[2], (s[1] - 1), s[0], 0, 0, 0, 0);
        end  =  new Date(e[2], (e[1] - 1), e[0], 0, 0, 0, 0);
        if ($('#reservation_guest_guests_' + mid).val() !== '0') {
            $('#addGuest').attr('disabled', false);
            $('#saveEditReserv').attr('disabled', false);
        }
        if ($(this).val() !== '0' && $('#reservation_guest_guests_' + mid).val() !== '0' && $('#guestForm').length > 0) {
            $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', false);
        }
        counterCalcCollection(start, end, mid);
    });
    jQuery(document).on('change', '[id^="reservation_guest_guests_"]', function () {
        var id = $(this).attr('id').split('_'),
            mid = id[id.length - 1],
            s,
            e,
            start,
            end;
        s = $('#show_reservation_guest_started_at_' + mid).val().split('.');
        e = $('#show_reservation_guest_ended_at_' + mid).val().split('.');
        //start = new Date(Date.UTC(s[2], (s[1] - 1), s[0], 0, 0, 0, 0));
        //end  =  new Date(Date.UTC(e[2], (e[1] - 1), e[0], 0, 0, 0, 0));
        start = new Date(s[2], (s[1] - 1), s[0], 0, 0, 0, 0);
        end  =  new Date(e[2], (e[1] - 1), e[0], 0, 0, 0, 0);
        window.Reservation.calcGuestsTotals(mid);
        $('#saveEditReserv').attr('disabled', false);

        if ($(this).val() === '12') {
            $.each(window.userlist, function (i, u) {
                $('#userlist').append('<li><a id="chooseuser_' + u.id + '">' + u.user_login_name + '</a></li>');
            });
            $('#guestFormClanOtherId').text(mid);
            $('#cross_reserv_user_list').modal({
                backdrop: 'static',
                keyboard: false
            });
            return false;
        }
        if ($(this).val() !== '0' && $('#guestForm').length > 0) {
            $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', false);
            $('#reservation_guest_num_' + mid).attr('disabled', false);
            $('#addGuest').attr('disabled', false);
            $('#saveEditReserv').attr('disabled', false);
        } else {
            $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', true);
            $('#reservation_guest_num_' + mid).attr('disabled', true);
        }
        window.Reservation.toggleOtherHost(mid, '', true);
        counterCalcCollection(start, end, mid);
    });
    jQuery(document).on('click', '#addGuest, [id^="reservation_guest_save_nights_"]', function (event) {
        event.preventDefault();
        var id = $(this).attr('id').split('_'),
            mid = id[id.length - 1],
            s,
            e,
            tooMuch = true,
            start,
            end;
        if ($('#show_reservation_guest_started_at_' + mid).length > 0) {
            s = $('#show_reservation_guest_started_at_' + mid).val().split('.');
            e = $('#show_reservation_guest_ended_at_' + mid).val().split('.');
            start = new Date(s[2], (s[1] - 1), s[0], 0, 0, 0, 0);
            end  =  new Date(e[2], (e[1] - 1), e[0], 0, 0, 0, 0);
            tooMuch = window.Reservation.freeBedChecker(start, end, mid);
        } else if(mid === 'addGuest') {
            s = $('#show_reservation_started_at').val().split('.');
            e = $('#show_reservation_ended_at').val().split('.');
            start = new Date(s[2], (s[1] - 1), s[0], 0, 0, 0, 0);
            end  =  new Date(e[2], (e[1] - 1), e[0], 0, 0, 0, 0);
            tooMuch = window.Reservation.freeBedChecker(start, end, mid);
        }
        $(this).closest('fieldset').removeClass('new');
        if (tooMuch) {
            window.Reservation.addGuestsForm({});
        }
    });
    jQuery(document).on('click', '[id^="deleteGuest_"]', function (e) {
        e.preventDefault();
        var id = $(this).attr('id').split('_')[1],
            last_id = window.parseInt($('[id^="deleteGuest_"]').length, 10) - 1;
        if ($('#reservation_guest_guests_' + id).val() === '12') {
            $('#userIdAb').val('');
        }
        window.Reservation.deleteGuest($('#reservation_guest_num_' + id).val(), $('#reservation_guest_started_at_' + id).val(), $('#reservation_guest_ended_at_' + id).val(), $('#reserv_guest_id_' + id).val(), id);
        $('#guestFormID_' + id).remove();
        $('#addGuest').attr('disabled', false);
        if ($('#reservation_guest_guests_' + last_id).val() !== '0') {
            $('#saveEditReserv').attr('disabled', false);
        }
        $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', false);
        window.Reservation.calcReservationTotals();
        window.Reservation.guestCounter();
    });
    jQuery(document).on('change', '[id^="reservation_guest_guests_"], [id^="reservation_guest_guests_"]', function () {
        var id = $(this).attr('id').split('_'),
            rowId = id[id.length - 1];
        $('#reservation_guest_num_' + rowId).focus().css({
            border: '3px solid #b7282e'
        })
    });
    jQuery(document).on('blur', '[id^="reservation_guest_num_"]', function (e) {
        e.preventDefault();
        $(this).css({
            border: '1px solid #b7282e'
        })
    });
    jQuery(document).on('click', '#saveEditReserv', function (e) {
        var saveStorageEntries = [],
        s = $('#show_reservation_started_at').val(),
        e = $('#show_reservation_ended_at').val(),
        st = s.split('.'),
        en = e.split('.'),
        start,
        sss,
        ss,
        isPreSaved = false,
        startDate = new Date(st[2], (window.parseInt(st[1], 10) - 1), st[0], 0, 0, 0),
        endDate = new Date(en[2], (window.parseInt(en[1], 10) - 1), en[0], 0, 0, 0);
        $.each($('[id^="reservation_guest_guests_"]'), function (i, n) {
            if (n.value === '0') {
                $('#guest_empty').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                e.preventDefault();
                return false;
            }
        });
        isPreSaved = (window.localStorage.hasOwnProperty('saveLocalStorage'));
        if (isPreSaved) {
            saveStorageEntries = JSON.parse(window.localStorage.getItem('saveLocalStorage'));
        } else {
            while (startDate.getTime() <= endDate.getTime()) {
                start = startDate.getFullYear() + '-' + window.smallerThenTen(startDate.getMonth()) + '-' + window.smallerThenTen(startDate.getDate());
                sss = window.smallerThenTen((window.parseInt(startDate.getMonth(), 10) + 1));
                ss = startDate.getFullYear() + '-' + sss + '-' + window.smallerThenTen(startDate.getDate());
                saveStorageEntries.push(
                    {
                        local_storage_date: ss + ' 00:00:00',
                        local_storage_number: (isNaN(window.parseInt(window.localStorage.getItem(start), 10))) ? window.settings.setting_num_bed : window.parseInt(window.localStorage.getItem(start), 10)
                    })
                startDate.setDate(startDate.getDate() + 1);
            }
        }
        Reservation.saveLocalStorageEntries(saveStorageEntries);
        window.localStorage.removeItem('saveLocalStorage');
    });
    jQuery(document).on('click', '[id^="chooseuser_"]', function () {
        var uid = $(this).attr('id').split('_'),
            text = $(this).text(),
            mid = $('#guestFormClanOtherId').text();
        window.Reservation.toggleOtherHost(mid, text, false);
        $('#cross_reserv_user_list').modal('hide');
        $('#userIdAb').val(uid[1]);

    });
});
