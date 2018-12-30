let V3Reservation = {
    allPeriods: {},
    periodID: null,
    globalDateFormat: 'dd.MM.yyyy',
    tmpDate: new Date(),
    freeBeds: {},
    periodEndDate: null,
    datePickerSettings: {},
    disabledDates: [],
    langStrings: window.reservationStrings,
    init: function (pID, afterValidation, startDate) {
        V3Reservation.periodID = pID;
        let period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID)),
            today = new Date(),
        otherClanDate = new Date();
        otherClanDate.setDate(otherClanDate.getDate() + 10);
        otherClanDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        if (period === null) {
            V3Reservation.writeLocalStorage(window.periods);
            period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID));
        }
        $('#periodID').val(V3Reservation.periodID);
        if (startDate < today) {
            startDate = today;
        }
        V3Reservation.periodEndDate = new Date(period.period_end);
        window.endDate = new Date(window.settings.setting_calendar_start);
        startDate.setHours(0, 0, 0, 0);
        window.endDate.setHours(0, 0, 0, 0);
        window.endDate.setFullYear(window.endDate.getFullYear() + parseInt(window.settings.setting_calendar_duration));
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
            title: window.datePickerPeriods[V3Reservation.formatDate(today, false, '_')].split('|')[1],
            todayHighlight: true,
            startDate: V3Reservation.formatDate(today),
            endDate: V3Reservation.formatDate(window.endDate),
            defaultViewDate: {
                year: today.getFullYear(),
                month: today.getMonth(),
                day: today.getDate()
            },
            immediateUpdates: true,
            beforeShowDay: function (Date) {
                let str = window.datePickerPeriods[V3Reservation.formatDate(Date, false, '_')].split('|'),
                    bothClasses = (str.length === 4) ? str[0] + '-datepicker-' + str[3] + ' ' : str[0] + '-datepicker-content ';
                $('.datepicker-title').html(str[1]).removeClass('WO-datepicker-title GU-datepicker-title')
                    .addClass(str[0] + '-datepicker-title');
                return {
                    enabled: (window.uID.clan_code === str[0] || (window.uID.clan_code !== str[0] && Date <= otherClanDate) || (window.endDate === Date)),
                    tooltip: str[1],
                    classes: bothClasses + 'pID_' + str[2],
                };
            }
        };
        $('.input-daterange').datepicker(V3Reservation.datePickerSettings).on('changeDate', function (e) {
            let classList;
            if (e.target.id === 'reservation_started_at') {
                if (e.date === undefined) {
                    return false
                }
                let endString = $('#reservation_ended_at').val().split('.'),
                    dates = {
                        startDate: new Date(e.date.valueOf()),
                        endDate: new Date(endString[2], (endString[1] - 1), endString[0], 0, 0, 0, 0),
                    };
                classList = $('.range-start').attr('class').split(' ');
                localStorage.setItem('startPID', classList[classList.length - 1]);
                V3Reservation.adaptChanged(dates, window.endDate, true);
            } else if (e.target.id === 'reservation_ended_at') {
                if (e.date === undefined) {
                    return false
                }
                let startString = $('#reservation_started_at').val().split('.'),
                    dates = {
                        startDate: new Date(startString[2], (startString[1] - 1), startString[0], 0, 0, 0, 0),
                        endDate: new Date(e.date.valueOf()),
                    };
                if ($('.range-end').attr('class') !== undefined) {
                    classList = $('.range-end').attr('class').split(' ');
                    if (classList[classList.length - 1] !== localStorage.getItem('startPID')) {
                        let period = $.parseJSON(localStorage.getItem('period_' + localStorage.getItem('startPID').split('_')[1]));
                        V3Reservation.periodEndDate = new Date(period.period_end);
                        $('#over_oeriod_date').html(V3Reservation.formatDate(V3Reservation.periodEndDate));
                        $('#over_period').show();
                        return false;
                    }
                }
                V3Reservation.adaptChanged(dates, window.endDate, false);
            } else if (e.target.id.indexOf('reservation_guest') > -1) {
                console.log(e.dates);
                let id = $(this).attr('id').split('_')[4];
                V3Reservation.calcNights(window.startGuestPicker[id].datepicker('getDate'), window.endGuestPicker[id].datepicker('getDate'), '#number_nights_' + id);
                V3Reservation.calcAllPrices();
            }
        });
        window.resStartPicker = $('.input-daterange').find('#reservation_started_at');
        window.resEndPicker = $('.input-daterange').find('#reservation_ended_at');
    },
    adaptChanged: function (dates, periodEndDate, isStart) {
        if (dates.startDate >= dates.endDate && isStart) {
            dates.endDate.setDate(dates.endDate.getDate() + 1);
            window.resEndPicker.datepicker('setStartDate', dates.endDate);
            window.resEndPicker.datepicker('setEndDate', periodEndDate);
            window.resStartPicker.datepicker('setEndDate', periodEndDate);
            window.resEndPicker.datepicker('setDate', dates.endDate);
            $('#reservation_ended_at').removeClass('noClick');
        }
        if (dates.startDate <= dates.endDate) {
            $('#clone_guest').show().attr('disabled', false);
        }
        for (let i = 0; i < window.startGuestPicker.length; i++) {
            if (dates.startDate > window.startGuestPicker[i].datepicker('getDate')) {
                window.startGuestPicker[i].datepicker('setDate', dates.startDate);
            }
            if (dates.endDate < window.endGuestPicker[i].datepicker('getDate')) {
                window.endGuestPicker[i].datepicker('setDate', dates.endDate);
            }
            console.log(window.startGuestPicker[i].datepicker('getDate'), window.endGuestPicker[i].datepicker('getDate'))
            window.startGuestPicker[i].datepicker('setStartDate', dates.startDate);
            window.startGuestPicker[i].datepicker('setEndDate', dates.endDate);
            window.endGuestPicker[i].datepicker('setStartDate', dates.startDate);
            window.endGuestPicker[i].datepicker('setEndDate', dates.endDate);
        }
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#reservation_nights_total');
        V3Reservation.setReservationHeaderText('#res_header_text', dates, $('#reservation_nights_total').text())
        V3Reservation.checkExistentReservation(dates.startDate, dates.endDate);
        V3Reservation.getFreeBeds(dates.startDate, dates.endDate, 'freeBeds_');
        $('#save_reservation').attr('disabled', false);
    },
    getFreeBeds: function (start, end, prefix) {
        let reservations = window.reservationsPerPeriod;
        if (reservations.length > 0) {
            V3Reservation.writeFreeBedsStorage(reservations, prefix, start, end);
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
    writeNewBeds: function (d) {
        let beds = parseInt($('#reservation_guest_num_total').text(), 10),
            total = parseInt(window.localStorage.getItem(d), 10);
        if (isNaN(total)) {
            total = parseInt(window.localStorage.getItem('new_' + d), 10);
        }
        if (isNaN(total)) {
            total = window.settings.setting_num_bed;
        }
        window.localStorage.setItem('new_' + d, (total - beds));
    },
    checkResBed: function (d) {
        let total = (parseInt(window.localStorage.getItem(d), 10) - 1);
        if (isNaN(total)) {
            total = (parseInt(window.localStorage.getItem('new_' + d), 10) - 1);
        }
        if (isNaN(total)) {
            total = 1;
        }
        return (total < 0);
    },
    getNewResBeds: function () {
        if ($('[id^="guests_date"]').length > 0) {
            return false;
        }
        let start = window.resStartPicker.datepicker('getDate'),
            end =  window.resEndPicker.datepicker('getDate');
        while (start < end) {
            let checkDateStr = start.getFullYear() + '-' + window.smallerThenTen(start.getMonth()) + '-' + window.smallerThenTen(start.getDate());
            if (V3Reservation.checkResBed(checkDateStr)) {
                V3Reservation.tooMuchBeds(checkDateStr);
                return false;
            }
            start.setDate(start.getDate() + 1);
        }
    },
    getNewBeds: function () {
        let beds;
        $.each($('[id^="guests_date"]'), function(i, n){
            let start = window.startGuestPicker[i].datepicker('getDate'),
                end =  window.endGuestPicker[i].datepicker('getDate');
            beds = parseInt($('#reservation_guest_num_' + i).val(), 10);
            V3Reservation.loopDates(start, end, '-', V3Reservation.writeNewBeds, false, beds)
        });
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
    formatDate: function (d, long, separator) {
        if (separator === undefined) {
            separator = '.';
        }
        if (typeof d === 'string') {
            return d;
        }
        let jsMonth = d.getMonth() + 1,
            day = (d.getDate() < 10) ? '0' + d.getDate() : d.getDate(),
            month = (jsMonth < 10) ? '0' + jsMonth : jsMonth;
        if (long) {
            return d.getDate() + separator + ' ' + window.fullMonthNames[d.getMonth()] + ' ' + d.getFullYear();
        }
        return day + separator + month + separator + d.getFullYear();
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
            dateString = V3Reservation.formatDate(dates.startDate) + ' - ' + V3Reservation.formatDate(dates.endDate) + numNight;
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
        V3Reservation.loopDates(start, end, '-', fillNewFreeBeds, false);
    },
    calcGuests: function () {
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
        V3Reservation.checkOccupiedBeds((total + 1));
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
        price = (window.rolesTaxes[guestGuest] * guestNight * guestNum);
        if (!isNaN(price)) {
            $('#price_' + id).text(price + '.-');
            $('#hidden_price_' + id).val(price);
            $('[id^="price_"]').trigger('input');
        }
        V3Reservation.calcGuests();
        return parseFloat(price);
    },
    calcAllPrices: function () {
        let total = 0,
            prices = $('[id^="hider_"]');
        $.each(prices, function (i, n) {
            total += V3Reservation.calcPrice(i);
        });
        $('#reservation_costs_total').html('<strong>' + total + '.-</strong>');
        $('#hidden_reservation_costs_total').val(total);
    },
    checkOccupiedBeds: function (total) {
        let tooMuch = false,
            totalBeds = window.settings.setting_num_bed,
            checkAvailableBeds = function (str) {
                let checkBedStorage = parseInt(window.localStorage.getItem(str), 10);
                if (isNaN(checkBedStorage)) {
                    checkBedStorage = totalBeds;
                }
                if ((checkBedStorage - total < 0) && !tooMuch) {
                    V3Reservation.tooMuchBeds(str);
                    tooMuch = true;
                }
            };
        if (!tooMuch) {
            V3Reservation.loopDates($('#reservation_started_at').val(), $('#reservation_ended_at').val(), '-', checkAvailableBeds, tooMuch);
        }
    },
    loopDates: function (startStr, endStr, separator, func, stop, args) {
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
            func(checkDateStr, args);
            start.setDate(start.getDate() + 1);
        }
    },
    tooMuchBeds: function (date) {
        let idBeds = $('#free-beds_' + date);
        $.each($('[id^="free-beds_"]'), function (i, n) {
            $(n).removeClass('tooMuchBeds');
        });
        idBeds.addClass('tooMuchBeds');
        $( '#all-free-beds-container').show();
        $('#free_beds>li>a').addClass('jquery-hover-a')
        $('#all-free-beds').scrollTop($('#all-free-beds').scrollTop() + (idBeds.position().top - 141));
        $.each($('[id^="guests_date_"]'), function (i, n) {
            $('#guests_date_' + i).find('[class^="col-"]:not(.no-hide)').slideDown('slow');
            $('#hide_guest_' + i).addClass('fa-caret-up').removeClass('fa-caret-down');
            //$('#hide_guest_' + i).trigger('click');
        });
        /*
        if (id !== undefined) {
            $('html, body').animate({
                scrollTop: $('#guests_date_' + id).offset().top
            }, 2000);
        }
        */
        $('#total_res')
            .addClass('alert-danger')
            .removeClass('alert-info');
        $('#no_free_beds').show();
        $('#save_reservation').attr('disabled', true);
        $('#clone_guest').attr('disabled', true);
    },
    enoughBeds: function (id) {
        $.each($('[id^="free-beds_"]'), function (i, n) {
            $(n).removeClass('tooMuchBeds');
        });
        $( '#all-free-beds-container').hide();
        $('#free_beds>li>a').removeClass('jquery-hover-a');
        $('#total_res')
            .removeClass('alert-danger')
            .addClass('alert-info');
        $('#no_free_beds').hide();
        $('#save_reservation').attr('disabled', false);
        $('#clone_guest').attr('disabled', false);
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
                if (!isNaN(parseInt(data, 10))) {
                    $('#reservation_exists').modal();
                    $('#edit_reservation_exists').attr('href', $('#edit_reservation_exists').attr('href') + '/' + data);
                }
            }
        })
    }
};
