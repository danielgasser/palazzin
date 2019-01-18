/**
 * Created by pc-shooter on 15.12.14.
 */
var firstCss = window.Reservation.firstAddedReservationCss,
    lastCss = window.Reservation.lastAddedReservationCss,
    interCss = window.Reservation.addedReservationCss,
    calDays,
    timer,
    firstEvent = 0,
    lastEvent = firstEvent + 1,
    cCounter = 0,
    inputDate = $('.date_type'),
    inputNum = document.querySelector('input[type="number"]');
inputDate.oninvalid = function (e) {
    "use strict";
    e.target.setCustomValidity('');
    if (!e.target.validity.valid) {
        e.target.setCustomValidity(window.langRes.warnings.not_in_period);
    }
};
jQuery(document).ready(function () {
    "use strict";

    if (!$('#choosenDates').hasOwnProperty('name')) {
        $('#choosenDates').html(window.langRes.no_chosen_dates)
            .addClass('btn-default')
            .removeClass('btn-success');
        $('#reset_res')
            .addClass('btn-default')
            .removeClass('btn-success');
    }
    jQuery('#calendar').Calendar({
        locale: window.locale,
        globalSettings: window.settings,
        weekDayNames: window.weekdayNames,
        weekdayShortNames: window.weekdayShortNames
    });
    jQuery(document).on('click', '#goReservTo_', function (e) {
        var start,
            end,
            begin,
            finish,
            inviter,
            modal_text = window.not_invited,
            modal_str,
            mapObj,
            otherHost,
            opts = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            },
            exID = $('body').find('[class^="otherHost_"]'),
            existentResiId = (exID.length > 0) ? exID[0].classList : null;
        otherHost = $('[class^="otherHost"]');
        if (otherHost.length === 0) {
            if ($(this).parent().parent().text().indexOf(window.langDialog.edit) > -1) {
                $('#cross_reserv').show();
                return false;
            }
            window.Reservation.showNewEditReservation(null);
        } else {
            start = $(otherHost[0]).attr('id').split('_');
            end = $(otherHost[$(otherHost).length - 1]).attr('id').split('_');
            begin = new Date(start[1], start[2], start[3], 0, 0, 0, 0);
            finish = new Date(end[1], end[2], end[3], 0, 0, 0, 0);
            inviter = $('[class^="guessWho_"]').children().eq(0).children().eq(0).text();
            mapObj = {
                user: inviter,
                start: window.showDate(begin, 'long'),
                end: window.showDate(finish, 'long')
            };
            modal_str = modal_text.replace(/user|start|end/gi, function (matches) {
                return mapObj[matches];
            });
            $('#not_invited_text').html(modal_str);
            if ($.inArray('reserv-btn', existentResiId) > -1) {
                $('#not_invited').show();
                return false;
            }
            if (existentResiId.length > 0) {
                $('#existentResId').val(existentResiId[1].split('_')[1]);
            } else {
                $('#existentResId').val('');
            }
            if (existentResiId[0].length > 0) {
                $('#userIdAb').val(existentResiId[0].split('_')[1]);
                localStorage.setItem('otherHost', existentResiId[1].split('_')[1]);
            } else {
                $('#userIdAb').val('xx');
                localStorage.setItem('otherHost', 'xx');
            }
            window.Reservation.showNewEditReservation(null);
        }
        window.localStorage.removeItem('saveLocalStorage');
    });
    jQuery(document).on('click', '#deleteEditReserv', function () {
        $('#delete_reservation').show();
    });
    jQuery(document).on('click', '#reset_storage, [href*="https://palazzin.ch/reservation"]', function (e) {
        e.preventDefault();
        window.localStorage.clear();
        //window.location.reload();
    });
    $.datepicker.setDefaults({
        showOn: 'focus',
        buttonImageOnly: false,
        buttonText: '',
        showButtonPanel: false
    });
    // datepicker overall
    $.datepicker.setDefaults($.datepicker.regional[window.locale.split('-')[0]]);
    jQuery(document).on('click', '#deleteRes', function (e) {
        e.preventDefault();
        window.Reservation.deleteReservation($('#res_id').val());
        window.Reservation.removeGuestForm();
        window.toggleStuff($('#editReservMenu'));
        cCounter = 0;
    });
    jQuery(document).on('click', '[id^="editThisReserv_"]', function (e) {
        e.preventDefault();
        var res_id = $(this).attr('id').split('_')[1];
        $('#leg h4').html(window.langRes.edit_res).append('<input type="hidden" value="1" id="isEditedRes">');
        window.localStorage.removeItem('saveLocalStorage');
        window.Reservation.editReservation(false, res_id);
    });
    jQuery(document).on('click', '#cancelEditReserv', function (e) {
        e.preventDefault();
        window.Reservation.removeGuestForm();
        window.toggleStuff($('#editReservMenu'));
        $.each($('.date_type'), function (i, n) {
            $(n).removeClass('hasDatepicker');
        });

        $('#reset_res').trigger('click');
    });

    jQuery(document).on('click', '#test', function (e) {
        e.preventDefault();
        window.localStorage.clear();
    });
    jQuery(document).on('click', '#cancel_no_free_beds', function (e) {
        e.preventDefault();
        $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', false);
        $('#addGuest').attr('disabled', false);
    });
    /*
jQuery(document).on('keyup mouseup', '[id^="reservation_guest_num_"]', function (e) {
   e.preventDefault();
   var guestMaxNum = parseInt(window.settings.setting_num_bed, 10) - 1;
   if (this.value > guestMaxNum) {
       //$('#freebeds_start').html(window.langRes.only_free_beds + ': ' + (window.settings.setting_num_bed - 1));
       $('#freebeds_start').html(window.langRes.only_free_beds + ': ' + (window.settings.setting_num_bed - 1) + ' ' +window.langRes.bedLabel + '<br>' + window.langRes.new_beds_at + ': ' + this.value);
       $('#no_free_beds').modal({
           backdrop: 'static',
           keyboard: false
       });
       if (this.value < 0) {
           $(this).val(1);
       } else {
           $(this).val(this.value);
       }
   }
    });
   */
    jQuery(document).on('s', '.date_type', function (e) {
        var old_val = this.value,
            instance = this;
        e.target.setCustomValidity('');
        if (!e.target.validity.valid) {
            e.target.setCustomValidity(window.langRes.warnings.not_in_period);
        }
    });
    jQuery(document).on('submit', 'form', function (e) {
        var form = $(this),
            sd = $('#show_reservation_started_at').val().split('.'),
            d = new Date(Date.UTC(sd[2], (sd[1] - 1), sd[0], 0, 0, 0, 0));
        d = new Date(sd[2], (sd[1] - 1), sd[0], 0, 0, 0, 0);
        $.each($('[id^="show_reservation_guest_started_at_"]'), function (i, n) {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'reservation_guest_started_at[]')
                .attr('value', $(n).val())
                .appendTo(form);
        });
        $.each($('[id^="show_reservation_guest_ended_at_"]'), function (i, n) {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'reservation_guest_ended_at[]')
                .attr('value', $(n).val())
                .appendTo(form);
        });
        window.setCurrentCalendarDate(d);
        window.putCalendarDateToSession(d);
        $('#reset_res').trigger('click');
    });

});
