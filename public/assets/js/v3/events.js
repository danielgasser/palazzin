$(document).on('click', '#timeliner > [id^="tl-"]', function (e) {
    let id = $(this).attr('id'),
        dateString = id.split('-'),
        startDate = new Date(dateString[1], (dateString[2] - 1), 1, 0, 0, 0);
    V3Reservation.init($(this).attr('data-period-id'), false, startDate);
});
$(document).on('click', '#reset_reservation', function (e) {
    e.preventDefault();
    //$('#timeliner').show();
    $(window.guestsDates).hide();
    $('[id^="show_res"]').hide();
});

/**
 * "Art des Gastes"
 */
$(document).on('change', '[id^="reservation_guest_guests_"]', function (e) {
    var id = $(this).attr('id').split('_')[3],
        guest_num_val = $('#reservation_guest_num_' + id).val();
    if ($(this).val() !== '0' && guest_num_val !== '') {
        $('#clone_guest_' + id).show().attr('disabled', false);
        V3Reservation.calcAllPrices(e);
        V3Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $(this).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
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
    $('#reservation_guest_price_' + id).text(window.rolesTaxes[this.value].toFixed(2))
    $('#hidden_reservation_guest_price_' + id).val(window.rolesTaxes[this.value].toFixed(2))
});

/**
 * Choose user if "Anderer Gastgeber"
 * ToDo
 */
jQuery(document).on('click', '[id^="chooseuser_"]', function () {
    var uid = $(this).attr('id').split('_'),
        text = $(this).text(),
        mid = $('#guestFormClanOtherId').text();
    window.V3Reservation.toggleOtherHost(mid, text, false);
    $('#cross_reserv_user_list').modal('hide');
    $('#userIdAb').val(uid[1]);
});

/**
 * Guest number
 */
jQuery(document).on('change', '[id^="reservation_guest_num_"]', function () {
    var num = parseInt($(this).attr('max'), 10),
        val = parseInt($(this).val(), 10),
        new_res = (window.localStorage.getItem('new_res') === '1'),
        id = $(this).attr('id').split('_')[3],
        g_id = (id === '0' || $(this).attr('id').split('_')[4] === undefined) ? '' : '_' + $(this).attr('id').split('_')[4],
        guest_start = $('#reservation_guest_started_at_' + id + g_id).val(),
        guest_start_string = guest_start.split('.'),
        guest_start_date = new Date(guest_start_string[2], (guest_start_string[1] - 1), guest_start_string[0], 0, 0 ,0, 0),
        guest_end = $('#reservation_guest_ended_at_' + id + g_id).val(),
        guest_end_string = guest_end.split('.'),
        guest_end_date = new Date(guest_end_string[2], (guest_end_string[1] - 1), guest_end_string[0], 0, 0 ,0, 0),
        guest_guest_val = $('#reservation_guest_guests_' + id + g_id).val(),
        provResObject = [];
    if (val > num) {
        $('#no_free_beds').modal({
            backdrop: 'static',
            keyboard: false
        });
        $(this).val(num);
    }
    if ($(this).val() !== '' && guest_guest_val !== '0') {
        $('#clone_guest_' + id + g_id).show().attr('disabled', false);
        while (guest_start_date < guest_end_date) {
            let strNew = guest_start_date.getFullYear() + '_' + window.smallerThenTen(guest_start_date.getMonth()) + '_' + window.smallerThenTen(guest_start_date.getDate()),
                strStorage = guest_start_date.getFullYear() + '-' + window.smallerThenTen(guest_start_date.getMonth()) + '-' + window.smallerThenTen(guest_start_date.getDate()),
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
            guest_start_date.setDate(guest_start_date.getDate() + 1);
        }
        V3Reservation.calcAllPrices();
        // ToDo when to call writeFreeBedsStorage?? on res. edit probably
        // V3Reservation.writeFreeBedsStorage(provResObject, 'freeBeds_',  new Date(guest_start_string[2], (guest_start_string[1] - 1), guest_start_string[0], 0, 0 ,0, 0), guest_end_date);
        V3Reservation.setGuestHeaderText(id + g_id, {start: guest_start, end: guest_end}, guest_guest_val, $(this).val(), $('#number_nights_' + id + g_id).text());
    } else {
        $('#clone_guest_' + id + g_id).show().attr('disabled', true);
    }
});

/**
 * Clone guest entry
 */
jQuery(document).on('click', '[id^="clone_guest_"]', function (e) {
    e.preventDefault();
    let div = $('#guests_date_0').clone(),
        splitted,
        start = new Date(V3Reservation.deFormatDate($('#reservation_guest_started_at_0').val(), '.', true)),
        end = new Date(V3Reservation.deFormatDate($('#reservation_guest_ended_at_0').val(), '.', true)),
        counter = $('[id^="guests_date_"]').length;
    $('#guest_entries').append(div);
    $(div).find('*').each(function () {
        if ($(this).attr('id') !== undefined) {
            splitted = $(this).attr('id').split('_');
            splitted.pop();
            $(this).attr('id', splitted.join('_') + '_' + counter);
        }
    });
    splitted = $(div).attr('id').split('_');
    splitted.pop();
    $(div).attr('id', splitted.join('_') + '_' + counter);
    $('#reservation_guest_num_' + counter).val('');
    $('#number_nights_' + counter).text('');
    $('#hidden_number_nights_' + counter).val('');
    $('#price_' + counter).text('');
    $('#hidden_price_' + counter).val('');
    $('#guest_title_' + counter).html('');
    $.each($('[id^="guests_date_"]'), function (i, n) {
        $('#guests_date_' + i).find('[class^="col-"]:not(.no-hide)').slideUp('slow');
        $('#hide_guest_' + i).addClass('fa-caret-down');
        $('#hide_guest_' + i).removeClass('fa-caret-up');
    });
    $('#clone_guest_' + counter).show().attr('disabled', true);
    $('#hide_guest_' + counter).trigger('click');
    V3Reservation.createIOSDatePicker(['#reservation_guest_started_at_' + counter, '#reservation_guest_ended_at_' + counter, '#number_nights_' + counter], start, end, V3Reservation.periodID)
    //V3Reservation.checkReservationMaxDates(start, end, window.datePickersStart, window.datePickersEnd, true);
    $('html, body').animate({
        scrollTop: $('#guests_date_' + counter).offset().top
    }, 2000)
});

/**
 * Remove guest entry
 */
jQuery(document).on('click', '[id^="remove_guest_"]', function () {
    var id = $(this).attr('id').split('_').pop();
    if ($('[id^="guests_date_"]').length === 1) {
        $('#no_guest_only_you').modal({
            backdrop: 'static',
            keyboard: false
        });
        return false;
    }
    $('#guests_date_' + id).remove();
});

/**
 * Toggle guest entry
 */
jQuery(document).on('click', '[id^="hider_"]', function () {
    var id = $(this).attr('id').split('_')[1];
    $('#guests_date_' + id).find('[class^="col-"]:not(.no-hide)').slideToggle('slow');
    $(this).children('#hide_guest_' + id).toggleClass('fa-caret-down');
    $(this).children('#hide_guest_' + id).toggleClass('fa-caret-up');
});

/**
 * Toggle Res entry
 */
jQuery(document).on('click', '[id^="hide_all_res"]', function () {
    $('[id^="show_res"]').find('[class^="col-"]').slideToggle('slow');
    $(this).children().toggleClass('fa-caret-down');
    $(this).children().toggleClass('fa-caret-up');
});

/**
 * Calc total nights
 */
jQuery(document).on('change', '#reservation_ended_at, #reservation_started_at', function () {
    var s = new Date(V3Reservation.deFormatDate($('#reservation_started_at').val(), '.', true)),
        e = new Date(V3Reservation.deFormatDate($('#reservation_ended_at').val(), '.', true));
    V3Reservation.calcNights(s, e, '#reservation_nights_total');
    V3Reservation.checkExistentReservation(s, e);
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
    var id = $(this).attr('id').split('_')[1];
    $('#hidden_price_' + id).val($(this).text())
});

/**
 * Show free beds
 */
jQuery(document).on('click', '#free_beds', function (e, visible) {
    let freeBeds = $('#all-free-beds');
    if (freeBeds.is(':visible') || visible) {
        $(this).animate({
            right: '0px'
        }, 500);
        freeBeds.hide(500);
        $('#hideAll').hide();
    } else {
        $(this).animate({
            right: '124px'
        }, 500);
        freeBeds.show(500);
        $('#hideAll').show();
    }
});

/**
 * Show periods
 */
jQuery(document).on('click', '#timeliner-div', function (e, visible) {
    let allPeriods = $('#timeliner');
    if (allPeriods.is(':visible') || visible) {
        $(this).animate({
            left: '0px'
        }, 500);
        allPeriods.hide(500);
        $('#hideAll').hide();
    } else {
        $(this).animate({
            left: '129px'
        }, 500);
        allPeriods.show(500);
        $('#hideAll').show();
    }
});

jQuery(document).on('click', '#hideAll', function () {
    $(this).hide();
    $('#timeliner-div').trigger('click', true);
    $('#free_beds').trigger('click', true);
});
jQuery(document).on('change', '#reservation_guest_num_total', function () {
    $.each($('[id^="free-beds_"]'), function (i, n) {
        $(n).removeClass('tooMuchBeds');
    });
});

/**
 * Show occupied beds
 */
jQuery(document).on('click', '#ap-button-cancel', function (e) {
    console.log(this)
});
/**
 * Show occupied beds
 */
jQuery('#ap-component-0>.ap-component-cont>.ap-component-data').data('horizontal', 0).data('vertical', 0).on('scroll', function () {
   console.log(this)
});


