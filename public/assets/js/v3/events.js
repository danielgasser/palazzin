$(document).ready(function () {
    alert('?');
});
$(document).on('click', '#timeliner > [id^="tl-"]', function (e) {
    e.preventDefault();
    var id = $(this).attr('id'),
        dateString = id.split('-'),
        period,
        today = new Date();
    today.setHours(0, 0, 0, 0);
    Reservation.periodID = $(this).attr('data-period-id');
    period = $.parseJSON(localStorage.getItem('period_' + Reservation.periodID));
    window.startDate = new Date(dateString[1], (dateString[2] - 1), 1, 0, 0, 0);
    window.endDate = new Date(period.period_end);
    window.startDate.setHours(0, 0, 0, 0);
    window.endDate.setHours(0, 0, 0, 0);
    Reservation.getOccupiedBeds(window.startDate, window.endDate);
    $('#timeliner').hide();
    $(window.guestsDates).show();
    $('#show_res').show();
    $('#reservationInfo>h4').html(window.reservationStrings.prior + ': ' + '<span class="' + period.clan_code + '-text">' + period.clan_description + '</span>');
    Reservation.createIOSDatePicker(['#reservation_started_at', '#reservation_ended_at', '#reservation_nights_total'], window.startDate, window.endDate, null, null, Reservation.periodID);
    Reservation.createIOSDatePicker(['#reservation_guest_started_at_0', '#reservation_guest_ended_at_0', '#number_nights_0'], window.startDate, window.endDate, null, null, Reservation.periodID);
});
$(document).on('click', '#reset_reservation', function (e) {
    e.preventDefault();
    $('#timeliner').show();
    $(window.guestsDates).hide();
    $('#show_res').hide();
});

/**
 * "Art des Gastes"
 */
$(document).on('change', '[id^="reservation_guest_guests_"]', function (e) {
    var id = $(this).attr('id').split('_')[3],
        guest_num_val = $('#reservation_guest_num_' + id).val();
    if ($(this).val() !== '0' && guest_num_val !== '') {
        $('#clone_guest_' + id).show().attr('disabled', false);
        Reservation.calcAllPrices();
        Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $(this).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
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
    window.Reservation.toggleOtherHost(mid, text, false);
    $('#cross_reserv_user_list').modal('hide');
    $('#userIdAb').val(uid[1]);
});

/**
 * Guest number
 */
jQuery(document).on('blur change mouseup keyup', '[id^="reservation_guest_num_"]', function () {
    var num = parseInt($(this).attr('max'), 10),
        val = parseInt($(this).val(), 10),
        id = $(this).attr('id').split('_')[3],
        guest_guest_val = $('#reservation_guest_guests_' + id).val();
    if (val > num) {
        $('#no_free_beds').modal({
            backdrop: 'static',
            keyboard: false
        });
        $(this).val(num);
    }
    if ($(this).val() !== '' && guest_guest_val !== '0') {
        $('#clone_guest_' + id).show().attr('disabled', false);
        Reservation.calcAllPrices();
        Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, guest_guest_val, $(this).val(), $('#number_nights_' + id).text());
    } else {
        $('#clone_guest_' + id).show().attr('disabled', true);
    }
});

/**
 * Clone guest entry
 */
jQuery(document).on('click', '[id^="clone_guest_"]', function () {
    var div = $('#guests_date_0').clone(),
        splitted,
        start = new Date(Reservation.deFormatDate($('#reservation_guest_started_at_0').val(), '.', true)),
        end = new Date(Reservation.deFormatDate($('#reservation_guest_ended_at_0').val(), '.', true)),
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
    Reservation.createIOSDatePicker(['#reservation_guest_started_at_' + counter, '#reservation_guest_ended_at_' + counter, '#number_nights_' + counter], start, end, null, null, Reservation.periodID)
    //Reservation.checkReservationMaxDates(start, end, window.datePickersStart, window.datePickersEnd, true);
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
jQuery(document).on('click', '#hide_all_res', function () {
    $('#show_res').find('[class^="col-"]').slideToggle('slow');
    $(this).toggleClass('fa-caret-down');
    $(this).toggleClass('fa-caret-up');
});

/**
 * Calc total nights
 */
jQuery(document).on('change', '#reservation_ended_at, #reservation_started_at', function () {
    var s = new Date(Reservation.deFormatDate($('#reservation_started_at').val(), '.', true)),
        e = new Date(Reservation.deFormatDate($('#reservation_ended_at').val(), '.', true));
    Reservation.calcNights(s, e, '#reservation_nights_total');
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
 * Show occupied beds
 */
jQuery(document).on('click', '#free_beds', function () {
    var freeBeds = $('#all-free-beds');
    if (freeBeds.is('visible')) {
        $(this).css({
            cursor: 'default'
        });
    } else {
        $(this).css({
            cursor: 'pointer'
        });
    }
    freeBeds.modal({
       dismissible: true,
       width: 130
   });
});

/**
 * Close modal
 */

/**
 * Show occupied beds
 */
jQuery('#ap-component-0>.ap-component-cont>.ap-component-data').data('horizontal', 0).data('vertical', 0).on('scroll', function () {
   console.log(this)
});


