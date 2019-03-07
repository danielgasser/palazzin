$(document).on('click', '#timeliner > [id^="tl-"]', function (e) {
    let id = $(this).attr('id'),
        dateString = id.split('-'),
        startDate = new Date(dateString[1], (dateString[2] - 1), 1, 0, 0, 0);
    V3Reservation.init($(this).attr('data-period-id'), false, startDate, true);
});
$(document).on('click', '#reset_reservation', function (e) {
    e.preventDefault();
    $(window.guestsDates).remove();
    $.each($('[id^="show_res"]').find('input, .show_reservation:not(button)'), function () {
        if (this.hasAttribute('type')) {
            this.value = ''
        } else {
            this.innerHTML = '';
        }
    });
    $('#reservation_costs_total').html('0.00');
    $('#reservation_guest_num_total').html('0');
    $('#clone_guest').attr('disabled', true);
});


/**
 * "Art des Gastes"
 */
$(document).on('change', '[id^="reservation_guest_guests_"]', function () {
    let id = $(this).attr('id').split('_')[3],
        guest_num_val = $('#reservation_guest_num_' + id).val(),
    dates = {startDate: $('#reservation_guest_started_at_' + id).val(), endDate: $('#reservation_guest_ended_at_' + id).val()};
    if ($(this).val() !== '0' && guest_num_val !== '') {
        $('#clone_guest').show().attr('disabled', false);
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#number_nights_' + id);
        V3Reservation.calcAllPrices();
        V3Reservation.setGuestHeaderText(id, dates, $(this).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
    } else {
        $('#clone_guest').show().attr('disabled', true);
    }
    if ($(this).value === '12') {
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
    if (this.value !== '0') {
        $('#reservation_guest_price_' + id).text(window.rolesTaxes[this.value]);
        $('#hidden_reservation_guest_price_' + id).val(window.rolesTaxes[this.value])
    } else {
        $('#reservation_guest_price_' + id).val('');
        $('#hidden_reservation_guest_price_' + id).val('')
    }
});

/**
 * Choose user if "Anderer Gastgeber"
 * ToDo
 */
jQuery(document).on('click', '[id^="chooseuser_"]', function () {
    let uid = $(this).attr('id').split('_'),
        text = $(this).text(),
        mid = $('#guestFormClanOtherId').text();
    window.V3Reservation.toggleOtherHost(mid, text, false);
    $('#cross_reserv_user_list').modal('hide');
    $('#userIdAb').val(uid[1]);
});

/**
 * Guest number & guest kind
 */
jQuery(document).on('change', '[id^="reservation_guest_num_"], [id^="reservation_guest_guests_"]', function () {
    //V3Reservation.getNewBeds();
});

/**
 * Guest number
 */
jQuery(document).on('input', '[id^="reservation_guest_num_"]:not(#reservation_guest_num_total)', function () {
    let num = parseInt($(this).attr('max'), 10),
        val = parseInt($(this).val(), 10),
        new_res = (window.localStorage.getItem('new_res') === '1'),
        id = $(this).attr('id').split('_')[3],
        guest_start = window.startGuestPicker[id].datepicker('getDate'),
        guest_end = window.endGuestPicker[id].datepicker('getDate'),
        guest_guest_val = $('#reservation_guest_guests_' + id).val(),
        provResObject = [];
    V3Reservation.enoughBeds(id);
    $('#save_reservation').attr('disabled', false);
    if (val > num) {
        $(this).val(num);
    }
    if ($(this).val() !== '' && guest_guest_val !== '0') {
        $('#clone_guest').show().attr('disabled', false);
        while (guest_start < guest_end) {
            let strNew = guest_start.getFullYear() + '_' + GlobalFunctions.smallerThenTen(guest_start.getMonth()) + '_' + GlobalFunctions.smallerThenTen(guest_start.getDate()),
                strStorage = strNew.split('_').join('-'),
                counter = 0;
            if (!new_res) {
                counter = (isNaN(window.parseInt(window.localStorage.getItem(strStorage), 10))) ? window.settings.setting_num_bed : window.parseInt(window.localStorage.getItem(strStorage), 10);
            }
            if (provResObject.hasOwnProperty('freeBeds_' + strNew)) {
                let c = window.parseInt(provResObject['freeBeds_' + strNew], 10) + parseInt($(this).val(), 10);
                provResObject.push({
                    ['freeBeds_' + strNew]: c
                });
            } else {
                let c = parseInt($(this).val(), 10);
                provResObject.push({
                    ['freeBeds_' + strNew]: c
                });
            }
            guest_start.setDate(guest_start.getDate() + 1);
        }
        let dates = {startDate: window.startGuestPicker[id].datepicker('getDate'), endDate: guest_end};
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#number_nights_' + id);
        V3Reservation.calcAllPrices();
        V3Reservation.setGuestHeaderText(id, dates, guest_guest_val, $(this).val(), $('#number_nights_' + id).text());
        V3Reservation.checkOccupiedBeds(parseInt($('#reservation_guest_num_total').text(), 10));
    } else {
        $('#clone_guest').show().attr('disabled', true);
    }
});

jQuery(document).on('click', '.dropup:not(#show-all-free-beds), .dropdown-toggle:not(#show-all-free-beds)', function () {
    $('#all-free-beds-container').hide();
    $('#show-all-free-beds').removeClass('open');
});

/**
 * Clone guest entry
 */
jQuery(document).on('click', '#clone_guest', function (e) {
    e.preventDefault();
    let counter = $('[id^="guests_date_"]').length,
        div = $(window.guestEntryView)
            .html(function (i, old) {
                return old.replace(new RegExp('_0', 'g'), '_' + counter);
            }).attr('id', 'guests_date_' + counter),
        today = window.resStartPicker.datepicker('getDate'),
        tomorrow = window.resEndPicker.datepicker('getDate'),
        guestDateEl;

    if (today === null) {
        today = new Date();
        today.setHours(0, 0, 0, 0);
    }
    $('#guest_entries').append(div);
    $('#reservation_guest_num_' + counter).val('');
    $('#number_nights_' + counter).text('');
    $('#hidden_number_nights_' + counter).val('');
    $('#price_' + counter).text('');
    $('#hidden_price_' + counter).val('');
    $('#reservation_guest_price_' + counter).val('');
    $('#guest_title_' + counter).html('');
    $('#clone_guest')
        .attr('disabled', true);
    guestDateEl = $('#guestDates_' + counter);
    guestDateEl.datepicker(V3Reservation.datePickerSettings);
    window.startGuestPicker[counter] = guestDateEl.find('#reservation_guest_started_at_' + counter);
    window.endGuestPicker[counter] = guestDateEl.find('#reservation_guest_ended_at_' + counter);
    window.startGuestPicker[counter].datepicker('setStartDate', today);
    window.startGuestPicker[counter].datepicker('setEndDate', tomorrow);
    window.startGuestPicker[counter].datepicker('setDate', today);
    window.endGuestPicker[counter].datepicker('setStartDate', today);
    window.endGuestPicker[counter].datepicker('setEndDate', tomorrow);
    window.endGuestPicker[counter].datepicker('setDate', tomorrow);
    guestDateEl.datepicker().on('changeDate', function (e) {
        let id = e.target.id.split('_')[4];
        V3Reservation.calcNights(window.startGuestPicker[id].datepicker('getDate'), window.endGuestPicker[id].datepicker('getDate'), '#number_nights_' + id);
        V3Reservation.getNewBeds();
        V3Reservation.calcAllPrices();
        V3Reservation.setGuestHeaderText(id, {startDate: window.startGuestPicker[id].datepicker('getDate'), endDate: window.endGuestPicker[id].datepicker('getDate')}, $('#reservation_guest_guests_' + id).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
    });
    if (counter > 0) {
        V3Reservation.calcNights(today, tomorrow, '#number_nights_' + counter);
    }

});

jQuery(document).on('DOMSubtreeModified', '#reservation_guest_num_total', function (e) {
    e.preventDefault();
    let id = $(this).attr('id').split('_').pop();
    if (parseInt(this.innerText, 10) <= parseInt(window.settings.setting_num_bed)) {

    }
});

/**
 * Remove guest entry
 */
jQuery(document).on('click', '[id^="remove_guest_"]', function () {
    let id = $(this).attr('id').split('_').pop();
    $('#confirm_delete_guest').attr('data-id', id);
    $('#delete_guest').modal({
        backdrop: 'static',
        keyboard: false
    });
    return false;
});

/**
 * Toggle free Beds
 */
jQuery(document).on('click', '#show-all-free-beds', function () {
    $('#free_beds-modal').modal({
        backdrop: 'static',
        keyboard: false
    });
});

/**
 * Clear reservation
 */
jQuery(document).on('click', '#clearReservation', function () {
    location.reload();
});

/**
 * Cancel edit existent reservation
 */
jQuery(document).on('click', '#cancel_reservation_exists', function () {
    let dates = {
        startDate: window.resStartPicker.datepicker('getDate'),
        endDate: window.resEndPicker.datepicker('getDate'),
    };
    V3Reservation.adaptChanged(dates, window.endDate, true);
    $('#reservation_exists').hide();
    return true;
});

/**
 * Remove guest entry
 */
jQuery(document).on('click', '#confirm_delete_guest', function () {
    let id = $('#confirm_delete_guest').attr('data-id'),
    dates = {
        start: null,
        end: null
    };
    $("#delete_guest").modal('hide');
    $('#guests_date_' + id).remove();
    $.each($('[id^="guests_date_"]'), function (i, n) {
        let child = $(n).find('*');
        let id = $(this).attr('id').split('_');
        id.pop();
        $(this).attr('id', id.join('_') + '_' + i);
        $.each(child, function () {
            if ($(this).attr('id') !== undefined) {
                let id = $(this).attr('id').split('_');
                id.pop();
                $(this).attr('id', id.join('_') + '_' + i);
            }
        });
    });
    window.startGuestPicker.splice(id, 1);
    window.endGuestPicker.splice(id, 1);
    $('[id^="reservation_guest_num_"]:not(#reservation_guest_num_total)').trigger('input');
    for (let i = 0; i < window.startGuestPicker.length; i++) {
        dates.startDate = window.startGuestPicker[i].datepicker('getDate');
        dates.endDate = window.endGuestPicker[i].datepicker('getDate');
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#reservation_nights_total');
        V3Reservation.setReservationHeaderText('#res_header_text', dates, $('#reservation_nights_total').text())
        V3Reservation.checkOccupiedBeds(parseInt($('#reservation_guest_num_total').text(), 10));
    }
    $('#clone_guest').attr('disabled', false);
});

/**
 * Delete Reservation
 */
jQuery(document).on('click', '[id^="delete_reservation_"]', function (e) {
    e.preventDefault();
    let id = $(this).attr('id').split('_')[2];
    $('#deleteRes').attr('data-id', id);
    $('#delete_reservation').modal({
        backdrop: 'static',
        keyboard: false
    });
});

/**
 * Delete Reservation
 */
jQuery(document).on('click', '#deleteRes', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    $('#delete_reservation').modal('hide');
    V3Reservation.deleteReservation(id);
});

/**
 * Change reservations end date
 */
jQuery(document).on('changeDate', '#reservation_ended_at', function (e) {
    V3Reservation.getNewResBeds();
});
/**
 * Change reservations end date
 */
jQuery(document).on('click', '#reservation_ended_at', function (e) {
    V3Reservation.firstTimeChangeEndDate++;
});

/**
 * Toggle guest entry
 */
jQuery(document).on('click', '[id^="hider_"]', function () {
    let id = $(this).attr('id').split('_')[1];
    $('#guests_date_' + id).find('[class^="col-"]:not(.no-hide)').slideToggle('slow');
    $(this).children('#hide_guest_' + id).toggleClass('fa-caret-down');
    $(this).children('#hide_guest_' + id).toggleClass('fa-caret-up');
});

/**
 * Toggle Res entry
 */
jQuery(document).on('click', '[id^="hide_all_res"]', function () {
    $('[id^="show_res"]').find('[class^="col-"]').not('#res_info').slideToggle('slow');
    $(this).children('span').toggleClass('fa-caret-down');
    $(this).children('span').toggleClass('fa-caret-up');
    //$('#res_info').clone();
    $( '[id^="hider_"]').trigger('click')
});

/**
 * Set input hidden nights
 */
jQuery(document).on('input propertychange paste', '[id^="number_nights_"]', function () {
    var id = $(this).attr('id').split('_')[2];
    $('#hidden_number_nights_' + id).val($(this).text())
});

/**
 * Set input hidden price
 */
jQuery(document).on('input propertychange paste change', '[id^="price_"]', function () {
    let id = $(this).attr('id').split('_')[1];
    $('#hidden_price_' + id).val(parseInt($('#reservation_guest_price_' + id).text(), 10))
});

/**
 * Show occupied beds
 */
jQuery('#ap-component-0>.ap-component-cont>.ap-component-data').data('horizontal', 0).data('vertical', 0).on('scroll', function () {
});

jQuery(document).on('#save_reservation', function (e) {
    e.preventDefault();

});
