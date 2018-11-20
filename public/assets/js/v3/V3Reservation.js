let V3Reservation = {
    allPeriods: {},
    periodID: null,
    globalDateFormat: 'dd.MM.yyyy',
    tmpDate: new Date(),
    freeBeds: {},
    datePickerSettings: {},
    disabledDates: [],
    langStrings: window.reservationStrings,
    init: function (pID, afterValidation, startDate) {
        V3Reservation.periodID = pID;
        let period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID)),
            today = new Date();
        today.setHours(0, 0, 0, 0);
        if (period === null) {
            V3Reservation.writeLocalStorage(window.periods);
            period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID));
        }
        $('#periodID').val(V3Reservation.periodID);
        if (startDate < today) {
            startDate = today;
        }
        window.endDate = new Date(period.period_end);
        startDate.setHours(0, 0, 0, 0);
        window.endDate.setHours(0, 0, 0, 0);
        //$('#timeliner-div').trigger('click', true);
        $('#hideAll').hide();
        $('[id^="show_res"]').show();
        $('#reservationInfo>h4').html(window.reservationStrings.prior + ': ' + '<span class="' + period.clan_code + '-text">' + period.clan_description + '</span>');
        V3Reservation.datePickerSettings = {
            format: "dd.mm.yyyy",
            weekStart: 1,
            todayBtn: "linked",
            clearBtn: true,
            language: 'de',
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            startDate: V3Reservation.formatDate(today),
            endDate: V3Reservation.formatDate(window.endDate),
            defaultViewDate: {
                year: today.getFullYear(),
                month: today.getMonth(),
                day: today.getDate()
            },
            immediateUpdates: true
        };
        $('.input-daterange').datepicker(V3Reservation.datePickerSettings);
        window.resStartPicker = $('.input-daterange').find('#reservation_started_at');
        $('#reservation_started_at').addClass('giveFocus');
        window.resEndPicker = $('.input-daterange').find('#reservation_ended_at');
        V3Reservation.getFreeBeds(startDate, window.endDate, false, 'reservation/get-per-period', 'freeBeds_');
    },
    adaptChanged: function (dates, isStart, id) {
        let el = (isStart) ? 'start' : 'end';
        if (id === undefined) {
            id = 0;
        }
        if (dates.startDate >= dates.endDate && isStart) {
            dates.endDate.setDate(dates.endDate.getDate() + 1);
            window.resEndPicker.datepicker('setStartDate', dates.endDate);
            window.resEndPicker.datepicker('setDate', dates.endDate);
            $('#reservation_ended_at').removeClass('noClick');
            $('#reservation_ended_at').addClass('giveFocus');
        }
        if (dates.startDate <= dates.endDate) {
            $('#clone_guest').show().attr('disabled', false);
            $('#clone_guest').addClass('giveFocus');
        }
        if (!isStart) {
            $('#reservation_started_at').removeClass('giveFocus');
            $('#reservation_ended_at').removeClass('giveFocus');
            $('#clone_guest').addClass('giveFocus');
        }
        for (let i = 0; i < window.startGuestPicker.length; i++) {
            window.startGuestPicker[i].datepicker('setStartDate', dates.startDate);
            window.startGuestPicker[i].datepicker('setEndDate', dates.endDate);
            window.endGuestPicker[i].datepicker('setStartDate', dates.startDate);
            window.endGuestPicker[i].datepicker('setEndDate', dates.endDate);
        }
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#reservation_nights_total');
        V3Reservation.calcNights(dates.startDate, dates.endDate, '[id^="number_nights_"]');
        V3Reservation.setReservationHeaderText('#res_header_text', dates, $('#reservation_nights_total').text())
        V3Reservation.checkExistentReservation(dates.startDate, dates.endDate);
    },
    getFreeBeds: function (start, end, edit, url , prefix) {
        let reservations = window.reservationsPerPeriod;
        if (reservations.length > 0) {
            V3Reservation.writeFreeBedsStorage(reservations, prefix, start, end);
            if (!edit) {
                V3Reservation.checkExistentReservation(start, end);
            }
        }
    },
    writeFreeBedsStorage: function (objects, prefix, start, end) {
        let len = (Object.keys(objects).length)
        for (let i = 0; i < len; i++) {
            let res = objects[i];
            Object.keys(res)
                .forEach(function (key) {
                    let c,
                        test = new RegExp('^' + prefix),
                        free = window.settings.setting_num_bed;
                    if (test.test(key)) {
                        c = key.split('_');
                        // total - (occupied + 1)
                        if (window.parseInt(res[key], 10) > 0) {
                            free -= (window.parseInt(res[key], 10) + 1)
                        }
                        window.localStorage.setItem(c[1] + '-' + c[2] + '-' + c[3], free);
                        V3Reservation.freeBeds[c[1] + '-' + c[2] + '-' + c[3]] = free;
                    }
                });
        }
        V3Reservation.setFreeBeds(start, end);
    },
    writeLocalStorage: function (data) {
        let d, i;
        for(i = 0; i < data.length; i++) {
            d = data[i];
            if (!window.localStorage.hasOwnProperty('period_' + i)) {
                window.localStorage.setItem('period_' + d.id, JSON.stringify(d));
            }
        }
    },
    createTimeLine: function (periods) {
        let s = window.settings.setting_calendar_start.split(' ')[0].split('-'),
            e =  window.parseInt(window.settings.setting_calendar_duration, 10),
            tl,
            start = new Date(),
            end = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
            monthStr,
            findP = function (obj, prop, v) {
                return $.grep(obj, function (p) {
                    return p[prop].indexOf(v) > -1;
                });
            },
            recursePeriodFinder = function (periods, prop, monthStr, start) {
                let tt = findP(periods, 'period_start_new', monthStr, start);
                let st = new Date(start);
                if (tt.length === 0) {
                    st.setMonth(st.getMonth() - 1)
                    monthStr = st.getFullYear() + '-' + window.smallerThenTen(st.getMonth() + 1);
                    tt = recursePeriodFinder(periods, 'period_start_new', monthStr, st);
                }
                return tt;
            },
            tt = [],
        i = 0;
        end.setFullYear(start.getFullYear() + e);
        end.setMonth(11);
        tl = $('#timeliner');
        tl.html('');
        while (start <= end) {
            monthStr = start.getFullYear() + '-' + window.smallerThenTen(start.getMonth() + 1);
            tl.append('<li id="tl-' + monthStr + '" class="graph-unit-container cal-unit-container">' +
                '<div class="graph-unit-day cal-unit-day">' + window.showDate(start, 'short') + '</div></li>');
            tt = recursePeriodFinder(periods, 'period_start_new', monthStr, start);
            $('#tl-' + monthStr)
                .addClass(tt[0].clan_code + '-solid')
                .attr('data_clan', tt[0].clan_code)
                .attr('data-period-id', tt[0].id)
                .append('<span style="text-align: center; display: block">' + tt[0].clan_description + '</span>');
            start.setMonth(start.getMonth() + 1);
            i++;
        }
    },
    calcNights: function (startDate, endDate, el) {
        if (typeof startDate !== 'object') {
            startDate = new Date(V3Reservation.deFormatDate(startDate, '.', true))
        }
        if (typeof endDate !== 'object') {
            endDate = new Date(V3Reservation.deFormatDate(endDate, '.', true))
        }
        let nights = parseInt(((endDate - startDate) / 1000 / 24 / 3600), 10),
        showNights = (nights < 0 || isNaN(nights)) ? 0 : nights;
        $(el).text(showNights);
        $('[id^="number_nights_"]').trigger('input');
        $('[id^="price_"]').trigger('input');
    },
    formatDate: function (d, long) {
        if (typeof d === 'string') {
            return d;
        }
        let jsMonth = d.getMonth() + 1,
            day = (d.getDate() < 10) ? '0' + d.getDate() : d.getDate(),
            month = (jsMonth < 10) ? '0' + jsMonth : jsMonth;
        if (long) {
            return d.getDate() + '. ' + window.fullMonthNames[d.getMonth()] + ' ' + d.getFullYear();
        }
        return day + '.' + month + '.' + d.getFullYear();
    },
    deFormatDate: function (d, sep, asString) {
        let tmp = d.split(sep),
            arr = [],
            month = parseInt(tmp[1], 10),
            day = parseInt(tmp[0], 10);
        arr[2] = (day < 10) ? '0' + day : day;
        arr[1] = (month < 10) ? '0' + month : month;
        arr[0] = tmp[2];
        if (asString) {
            return arr.join(sep)
        }
        return arr;
    },
    checkReservationMaxDates: function (start, end, anyPickerStart, anyPickerEnd, onInit) {
        let startDate = (typeof start !== 'object') ? new Date(V3Reservation.deFormatDate(start, '.', true)) : start,
        endDate = (typeof end !== 'object') ? new Date(V3Reservation.deFormatDate(end, '.', true)) : end,
        startDateInput,
        endDateInput;
        $.each($('[id^="reservation_guest_started_at_"]'), function (i, n) {
            startDateInput = new Date(V3Reservation.deFormatDate($(n).val(), '.', true));
            endDateInput = new Date(V3Reservation.deFormatDate($('#reservation_guest_ended_at_' + i).val(), '.', true));
            if (startDate > startDateInput || onInit) {
                anyPickerStart[i + 1].setSelectedDate(startDate);
                anyPickerStart[i + 1].setMinimumDate(startDate);
                anyPickerStart[i + 1].setting.i18n.headerTitle = V3Reservation.langStrings.arrival + ': ' + V3Reservation.formatDate(startDate, true)
            }
            if (endDate < endDateInput || onInit) {
                anyPickerEnd[i + 1].setSelectedDate(endDate);
                anyPickerEnd[i + 1].setMaximumDate(endDate);
                anyPickerEnd[i + 1].setting.i18n.headerTitle = V3Reservation.langStrings.depart + ': ' + V3Reservation.formatDate(endDate, true)
            }
            V3Reservation.calcNights(start, end, '#number_nights_' + i);
        });
        //V3Reservation.calcNights(start, end, '#reservation_nights_total');
    },
    setGuestHeaderText: function (id, dates, guestKind, numberGuest, numberNight) {
        let guest_kind = (guestKind == '0') ? '' : '&nbsp;x&nbsp;' + window.rolesTrans[guestKind],
            nights = (parseInt(numberNight, 10) === 1) ? window.reservationStrings.night : window.reservationStrings.nights,
            num_guest = (numberGuest == '') ? '' : ': ' + numberGuest,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' ' + nights,
            dateString = window.guestTitle + V3Reservation.formatDate(dates.startDate) + ' - ' + V3Reservation.formatDate(dates.endDate) + numNight + num_guest + guest_kind;
        $('#guest_title_' + id).html(dateString);
    },
    setReservationHeaderText: function (id, dates, numberNight) {
        let nights = (parseInt(numberNight, 10) === 1) ? window.reservationStrings.night : window.reservationStrings.nights,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' ' + nights,
            dateString = ': ' + V3Reservation.formatDate(dates.startDate) + ' - ' + V3Reservation.formatDate(dates.endDate) + numNight;
        $('#res_header_text').html(dateString);
    },
    setFreeBeds: function (start, end) {
        $('[id^="free-beds_"]').html('');
        let fillNewFreeBeds = function (str) {
            let beds = window.localStorage.getItem(str),
                bedNumberShow = (beds === null) ? window.settings.setting_num_bed : window.smallerThenTen(window.parseInt(beds, 10), true),
                showBedDateStr = str.split('-'),
                dateBed = new Date(showBedDateStr[0], showBedDateStr[1], showBedDateStr[2], 0, 0, 0, 0),
                bedString = showBedDateStr[2] + '.' + window.smallerThenTen((parseInt(showBedDateStr[1], 10) + 1)) + '.' + showBedDateStr[0] + ': <span style="text-align: right"><strong>' + bedNumberShow + '</strong></span>';
            if ($('#free-beds_' + str).length > 0) {
                $('#free-beds_' + str).html(bedString);
            } else {
                $('#all-free-beds').append('<div id="free-beds_' + str + '">' + bedString + '</div>');
            }
            if (parseInt(bedNumberShow, 10) <= 0 && !V3Reservation.disabledDates.find(x => x.val === V3Reservation.formatDate(dateBed))) {
                V3Reservation.disabledDates.push(
                    {
                        val: dateBed
                    }
                )
            }};
        V3Reservation.loopDates(start, end, '-', fillNewFreeBeds);
        console.log(V3Reservation.disabledDates);
        $('#free_beds')
            .show();
    },
    calcGuests: function (id) {
        let i,
            total = 0,
            tmp,
            guests = $('[name="reservation_guest_num[]"]');
        for (i = 0; i < guests.length; i++) {
            tmp = guests[i].value;
            if (tmp === undefined || tmp === '') {
                tmp = 0;
            }
            total += parseInt(tmp, 10);
        }
        $('#reservation_guest_num_total').text((total + 1));
        $('#hidden_reservation_guest_num_total').val(total);
        V3Reservation.checkOccupiedBeds((total + 1), id);
        return total;
    },
    calcPrice: function (id) {
        let guestGuest = $('#reservation_guest_guests_' + id).val(),
            guestNight,
            guestNumVal,
            guestNum,
            price;
        if (guestGuest === '0') {
            return 0.0;
        }
        guestNight = parseInt($('#number_nights_' + id).text(), 10);
        guestNumVal = parseInt($('#reservation_guest_num_' + id).val(), 10);
        guestNum = (isNaN(guestNumVal)) ? 0 : guestNumVal;
        price = (window.rolesTaxes[guestGuest] * guestNight * guestNum).toFixed(2).replace(",", ".");
        if (!isNaN(price)) {
            $('#price_' + id).text(price);
            $('#hidden_price_' + id).val(price);
            $('[id^="price_"]').trigger('input');
        }
        V3Reservation.calcGuests(id);
        return parseFloat(price);
    },
    calcAllPrices: function () {
        let total = 0,
            prices = $('[id^="hider_"]');
        $.each(prices, function (i, n) {
            total += V3Reservation.calcPrice(i);
        });
        $('#reservation_costs_total').text(total.toFixed(2).replace(",", "."));
        $('#hidden_reservation_costs_total').val(total.toFixed(2).replace(",", "."));
    },
    checkOccupiedBeds: function (total, id) {
        let tooMuch = false,
            totalBeds = window.settings.setting_num_bed,
            checkAvailableBeds = function (str) {
                let checkBedStorage = parseInt(window.localStorage.getItem(str), 10);
                if (isNaN(checkBedStorage)) {
                    checkBedStorage = totalBeds;
                }
                if ((checkBedStorage - total < 0) && !tooMuch) {
                    V3Reservation.tooMuchBeds(str, id);
                    tooMuch = true;
                }
            };
        $('#reservation_guest_num_total').tooltip('hide');
        if (!tooMuch) {
            V3Reservation.loopDates($('#reservation_started_at').val(), $('#reservation_ended_at').val(), '-', checkAvailableBeds, tooMuch);
        }
    },
    loopDates: function (startStr, endStr, separator, func, stop) {
        let s,
            start,
            e,
            end,
            checkDateStr;
        if (stop) {
            return false;
        }
        if (typeof startStr === 'string') {
            s = startStr.split('.');
            start = new Date(window.parseInt(s[2], 10), (s[1] - 1), window.parseInt(s[0], 10), 0, 0, 0, 0);
            e = endStr.split('.');
            end = new Date(window.parseInt(e[2], 10), (e[1] - 1), window.parseInt(e[0], 10), 0, 0, 0, 0);
        } else {
            start = new Date(startStr);
            end = new Date(endStr);
        }
        while (start < end) {
            checkDateStr = start.getFullYear() + separator + window.smallerThenTen(start.getMonth()) + separator + window.smallerThenTen(start.getDate());
            func(checkDateStr);
            start.setDate(start.getDate() + 1);
        }
    },
    tooMuchBeds: function (date, id) {
        let idBeds = $('#free-beds_' + date);
        $.each($('[id^="free-beds_"]'), function (i, n) {
            $(n).removeClass('tooMuchBeds');
        });
        idBeds.addClass('tooMuchBeds');
        $( '#all-free-beds-container').show();
        $('#all-free-beds').scrollTop($('#all-free-beds').scrollTop() + (idBeds.position().top - 141));
        $('#reservation_guest_num_' + id)
            .addClass('giveFocus');
        $('#reservation_guest_num_total').tooltip('show')
            .addClass('giveFocus');
        $('#save_reservation').attr('disabled', true);
    },
    checkExistentReservation: function (s, e) {
        let start = s.getFullYear() + '-' + window.smallerThenTen((s.getMonth() + 1)) + '-' + window.smallerThenTen(s.getDate()),
            end = e.getFullYear() + '-' + window.smallerThenTen((e.getMonth() + 1)) + '-' + window.smallerThenTen(e.getDate());
        $.ajax({
            url: 'new_reservation/check_existent',
            method: 'POST',
            data: {
                start: start,
                end: end,
                "_token": window.token,
            },
            success: function (data) {
                if (data !== "") {
                    $('#reservation_exists').modal();
                    $('#edit_reservation_exists').attr('href', $('#edit_reservation_exists').attr('href') + '/' + data);
                }
            }
        })
    }
};
