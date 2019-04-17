$(document).on('click', '#timeliner > [id^="tl-"]', function (e) {
    let id = $(this).attr('id'),
        dateString = id.split('-'),
        startDate = new Date(dateString[1], (dateString[2] - 1), 1, 0, 0, 0);
    if (localStorage.getItem('new_res') !== '0') {
        V3Reservation.initNew($(this).attr('data-period-id'), startDate, true);
    }
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
    $('[id^="clone_guest_"]').attr('disabled', true);
});

$(document).on('change', 'th.clear', function (e) {
   console.log(this)
});

/**
 * "Art des Gastes"
 */
$(document).on('change', '[id^="reservation_guest_guests_"]', function () {
    let id = $(this).attr('id').split('_')[3],
        guest_num_val = $('#reservation_guest_num_' + id).val(),
    dates = {startDate: $('#reservation_guest_started_at_' + id).val(), endDate: $('#reservation_guest_ended_at_' + id).val()};
    $('#save_reservation').attr('disabled', true);
    if ($(this).val() !== '0' && guest_num_val !== '') {
        $('#clone_guest_' + id).show().attr('disabled', false);
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#number_nights_' + id);
        V3Reservation.calcAllPrices();
        V3Reservation.setGuestHeaderText(id, dates, $(this).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
        $('#save_reservation').attr('disabled', false);
    } else {
        $('#clone_guest_' + id).show().attr('disabled', true);
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
        $('#reservation_guest_price_' + id).text(parseFloat(window.rolesTaxes[this.value]).toFixed(2));
        $('#hidden_reservation_guest_price_' + id).val(window.rolesTaxes[this.value]);
        $('#reservation_guest_num_' + id).show().attr('disabled', false);
    } else {
        $('#reservation_guest_price_' + id).val('');
        $('#hidden_reservation_guest_price_' + id).val('')
        $('#save_reservation').attr('disabled', true);
    }
});

/**
 * Guest number
 */
$(document).on('input', '[id^="reservation_guest_num_"]:not(#reservation_guest_num_total)', function () {
    let num = parseInt($(this).attr('max'), 10),
        val = parseInt($(this).val(), 10),
        new_res = (window.localStorage.getItem('new_res') === '1'),
        id = $(this).attr('id').split('_')[3],
        guest_start = window.startGuestPicker[id].datepicker('getDate'),
        guest_end = window.endGuestPicker[id].datepicker('getDate'),
        guest_guest_val = $('#reservation_guest_guests_' + id).val(),
        provResObject = [];
    V3Reservation.enoughBeds(id);
    $('#save_reservation').attr('disabled', true);
    if (val > num) {
        $(this).val(num);
    }
    if ($(this).val() !== '' && guest_guest_val !== '0') {
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
            $('#save_reservation').attr('disabled', false);
        }
        let dates = {startDate: window.startGuestPicker[id].datepicker('getDate'), endDate: guest_end};
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#number_nights_' + id);
        V3Reservation.calcAllPrices();
        V3Reservation.setGuestHeaderText(id, dates, guest_guest_val, $(this).val(), $('#number_nights_' + id).text());
        $('#hidden_price_' + id).val(window.rolesTaxes[guest_guest_val])
        $('#reservation_guest_price_' + id).text(parseFloat(window.rolesTaxes[guest_guest_val]).toFixed(2))
        $('#clone_guest_' + id).show().attr('disabled', false);
    } else {
        $('[id^="clone_guest_"]').show().attr('disabled', true);
    }
    V3Reservation.checkOccupiedBeds(parseInt($('#reservation_guest_num_total').text(), 10));

});

$(document).on('click', '.dropup:not(#show-all-free-beds), .dropdown-toggle:not(#show-all-free-beds)', function () {
    $('#all-free-beds-container').hide();
    $('#show-all-free-beds').removeClass('open');
});

/**
 * Clone guest entry
 */
$(document).on('click', '[id*="clone_guest_"]', function (e) {
    e.preventDefault();
    let counter = $('[id^="guests_date_"]').length,
        div = $(window.guestEntryView)
            .html(function (i, old) {
                return old.replace(new RegExp('_0', 'g'), '_' + counter);
            }).attr('id', 'guests_date_' + counter),
        today = window.resStartPicker.datepicker('getDate'),
        tomorrow = window.resEndPicker.datepicker('getDate'),
        guestDateEl;
    $('#addZeroGuest').hide();

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
    $('[id^="clone_guest_"]')
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
    $.each($('input:not([type="hidden"]), select'), function () {
        window.allInputs.push($(this).attr('id'));
    });
    $('html, body').animate({ scrollTop: $(document).height() }, "fast")
});

$(document).on('click', '.close-datepicker', function (e) {
    let id = $(this).attr('id').split('_')[4];
    if ($('.datepicker').is(':visible')) {
        $.each(window.startGuestPicker, function () {
            $(this).datepicker('hide')
        });
        window.resStartPicker.datepicker('hide')
        window.resEndPicker.datepicker('hide')
    }
});
$(document).on('DOMSubtreeModified', '#reservation_guest_num_total', function (e) {
    e.preventDefault();
    let id = $(this).attr('id').split('_').pop();
    if (parseInt(this.innerText, 10) <= parseInt(window.settings.setting_num_bed)) {
    }
});

$(document).on('change', 'input:not([type="hidden"]), select', function (e) {
    e.preventDefault();
    let id = $(this).attr('id'),
        key = GlobalFunctions.arraySearch(window.allInputs, id);
    $('input:not([type="hidden"]), select').removeClass('giveFocus');
    $('#' + window.allInputs[key + 1]).addClass('giveFocus');
});

/**
 * Toggle free Beds
 */
$(document).on('click', '#show-all-free-beds', function () {
    $('#free_beds-modal').modal({
        backdrop: 'static',
        keyboard: false
    });
});

/**
 * Clear reservation
 */
$(document).on('click', '#clearReservation', function () {
    location.reload();
});

/**
 * Cancel edit existent reservation
 */
$(document).on('click', '#cancel_reservation_exists', function () {
    let dates = {
        startDate: window.resStartPicker.datepicker('getDate'),
        endDate: window.resEndPicker.datepicker('getDate'),
    };
    V3Reservation.adaptChanged(dates, window.endDate);
    $('#reservation_exists').hide();
    return true;
});

/**
 * Remove guest entry
 */
$(document).on('click', '[id^="remove_guest_"]', function () {
    let id = $(this).attr('id').split('_').pop();
    $('#confirm_delete_guest').attr('data-id', id);
    $('#delete_guest').modal({
        backdrop: 'static',
        keyboard: false
    });
    return false;
});

/**
 * Remove guest entry
 */
$(document).on('click', '#confirm_delete_guest', function () {
    let id = $('#confirm_delete_guest').attr('data-id'),
    guest_id = $('#guest_id_' + id).val();
    $("#delete_guest").modal('hide');
    if (guest_id === undefined) {
        V3Reservation.deleteGuestEntry(id);
        return false;
    }
    $.ajax({
        method: 'POST',
        url: '/delete_guest',
        data: {
            guest_id: guest_id
        },
        success: function (data) {
            if ($.parseJSON(data).hasOwnProperty('success')) {
                V3Reservation.deleteGuestEntry(id);
            }
        }
    });
});

/**
 * Delete Reservation
 */
$(document).on('click', '[id^="delete_reservation_"]', function (e) {
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
$(document).on('click', '#deleteRes', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    $('#delete_reservation').modal('hide');
    V3Reservation.deleteReservation(id);
});

/**
 * Change reservations end date
 */
$(document).on('changeDate', '#reservation_ended_at', function () {
    let st = document.getElementById('reservation_started_at').value.split('.'),
        et = this.value.split('.'),
        start = new Date(st[2], (parseInt(st[1], 10) - 1), st[0]),
        end = new Date(et[2], (parseInt(et[1], 10) - 1), et[0]);
    V3Reservation.getNewResBeds();
    V3Reservation.checkExistentReservation(start, end);
});
/**
 * Change reservations end date
 */
$(document).on('blur', '#reservation_started_at', function (e) {
    V3Reservation.onHide(e);
});

/**
 * Set input hidden nights
 */
$(document).on('input propertychange paste', '[id^="number_nights_"]', function () {
    var id = $(this).attr('id').split('_')[2];
    $('#hidden_number_nights_' + id).val($(this).text())
});

/**
 * Set input hidden price
 */
$(document).on('input propertychange paste change', '[id^="price_"]', function () {
    let id = $(this).attr('id').split('_')[1];
    $('#hidden_price_' + id).val(parseInt($('#reservation_guest_price_' + id).text(), 10))
});

/**
 * Show occupied beds
 */
$('#ap-component-0>.ap-component-cont>.ap-component-data').data('horizontal', 0).data('vertical', 0).on('scroll', function () {
});

$(document).on('#save_reservation', function (e) {
    e.preventDefault();

});
// for min
