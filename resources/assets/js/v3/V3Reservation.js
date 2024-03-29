var V3Reservation = {
    allPeriods: {},
    periodID: null,
    fromPeriod: false,
    globalDateFormat: 'dd.MM.yyyy',
    tmpDate: new Date(),
    freeBeds: {},
    tooMuch: false,
    periodEndDate: null,
    datePickerSettings: {},
    disabledDates: [],
    firstTimeChangeEndDate: 0,
    langStrings: window.reservationStrings,
    giveFocus: function (elFocus) {
        $('input:not([type="hidden"]), select').removeClass('giveFocus');
        if (elFocus === undefined) {
            return false;
        }
        $(elFocus).addClass('giveFocus');
    },
    noClick: function (lock) {
        if (lock) {
            $.each($('[id^="reservation_guest_ended_at_"]'), function (i, n) {
                $(n).addClass('noClick');
                $('#reservation_guest_guests_' + i).addClass('noClick');
                $('#reservation_guest_num_' + i).addClass('noClick');
                $('#reservation_guest_started_at_' + i).addClass('noClick');
            });
            $('#reservation_ended_at').addClass('noClick');
            $('#show-all-free-beds').addClass('noClick');
        } else {
            $.each($('[id^="reservation_guest_ended_at_"]'), function (i, n) {
                $(n).removeClass('noClick');
                $('#reservation_guest_guests_' + i).removeClass('noClick');
                $('#reservation_guest_num_' + i).removeClass('noClick');
                $('#reservation_guest_started_at_' + i).removeClass('noClick');
            });
            $('#reservation_ended_at').removeClass('noClick');
            $('#show-all-free-beds').removeClass('noClick');
        }
        if ($('#reservation_started_at').val() !== '') {
            $('#reservation_ended_at').removeClass('noClick');
            $('#show-all-free-beds').removeClass('noClick');
        }
    },
    onHide: function (e) {
        let classList,
            startString,
            endString,
        dates;
        if (e.target.id === 'reservation_started_at') {
            if (e.date === undefined) {
                V3Reservation.noClick(true);
                return false;
            }
            V3Reservation.noClick(false);
            endString = $('#reservation_ended_at').val().split('.');
            dates = {
                    startDate: new Date(e.date.valueOf()),
                    endDate: new Date(endString[2], (endString[1] - 1), endString[0], 0, 0, 0, 0),
            };
            V3Reservation.giveFocus('#reservation_ended_at');
            //V3Reservation.adaptChanged(dates, window.endDate, true);
        } else if (e.target.id === 'reservation_ended_at') {
            if (e.date === undefined) {
                return false;
            }
            startString = $('#reservation_started_at').val().split('.');
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
                    window.resEndPicker.datepicker('setDate', V3Reservation.periodEndDate);
                  //  V3Reservation.adaptChanged(dates, window.endDate, false);
                    $('#over_period').show();
                    return false;
                }
            }
            if ($('[id^="guests_date_"]').length === 1) {
                $('#guests_date_0').show();
                V3Reservation.giveFocus('#reservation_guest_started_at_0');
            }
          //  V3Reservation.adaptChanged(dates, window.endDate, false);
        } else if (e.target.id.indexOf('reservation_guest') > -1) {
            if (e.date === undefined) {
                return false;
            }

            let id = $('#' + e.target.id).attr('id').split('_')[4];
            if (id === undefined) {
                id = 0;
            }
            if (e.target.id.indexOf('start') > -1 && $('#reservation_started_at').val() === '') {
                window.resStartPicker.datepicker('setDate', window.startGuestPicker[id].datepicker('getDate'));
                V3Reservation.giveFocus('#reservation_guest_ended_at_' + id);
            }
            if (e.target.id.indexOf('end') > -1 && $('#reservation_ended_at').val() === '') {
                window.resEndPicker.datepicker('setDate', window.endGuestPicker[id].datepicker('getDate'));
            }
            V3Reservation.calcNights(window.startGuestPicker[id].datepicker('getDate'), window.endGuestPicker[id].datepicker('getDate'), '#number_nights_' + id);
            V3Reservation.calcAllPrices();
            startString = $('#reservation_started_at').val().split('.');
            dates = {
                startDate:window.startGuestPicker[id].datepicker('getDate'),
                endDate: window.endGuestPicker[id].datepicker('getDate'),
            };
        }
        $('#reservation_guest_guests_0').attr('disabled', false);
        $('#reservation_guest_num_0').attr('disabled', false);
        V3Reservation.adaptChanged(dates, window.endDate);
    },
    addBeforeShowDayEdit: function (d, otherClanDate, isEdit) {
        let numBeds = window.settings.setting_num_bed,
            dateStr = d.getFullYear() + '_' + GlobalFunctions.smallerThenTen(d.getMonth()) + '_' + GlobalFunctions.smallerThenTen(d.getDate()),
            dateStrUid = parseInt(window.newAllGuestBeds['freeBeds_' + dateStr + 'uID'], 10),
            pickerDateStr = V3Reservation.formatDate(d, false, '_'),
            occupied = isNaN(parseInt(window.newAllGuestBeds['freeBeds_' + dateStr], 10)) ? 0 : parseInt(window.newAllGuestBeds['freeBeds_' + dateStr], 10),
            oB = 0,
            today = new Date(),
            ownClass = (window.newUserRes.hasOwnProperty('user_Res_Dates_' + dateStr) && !isEdit) ? ' myRes' : '',
            occupiedBeds = (window.newAllGuestBeds['freeBeds_' + dateStr] === undefined) ? '<span class="freeB">' + numBeds + '</span><span class="occB">0</span>' : '<span class="freeB">' + (numBeds - occupied) + '</span><span class="occB">' + occupied + '</span>',
            str = (window.datePickerPeriods[pickerDateStr] !== undefined) ? window.datePickerPeriods[pickerDateStr].split('|') : '',
            priority = $('.priority>li>.' + str[0] + '-datepicker-content'),
            bothClasses = (str.length === 4) ? str[0] + '-datepicker-' + str[3] + ' ' : str[0] + '-datepicker-content ',
            isEnabled = false,
            hasFreeBeds = (occupied < numBeds),
            returnObject;
        today.setHours(0, 0, 0, 0);
        if (isEdit) {
            if (d.getTime() >= today.getTime()) {
                isEnabled = true;
            }
            else if (window.uID.clan_code !== str[0] && d <= otherClanDate && d.getTime() >= today.getTime()) {
                isEnabled = true;
            } else {
                isEnabled = false;
            }
        } else {
            if (d.getTime() >= today.getTime()) {
                isEnabled = true;
            } else if (window.uID.clan_code === str[0]) {
                isEnabled = true;
            }else if (window.uID.clan_code !== str[0] && d <= otherClanDate && d.getTime() >= today.getTime()) {
                isEnabled = true;
            } else if (window.endDate === d) {
                isEnabled = true;
            } else if (window.newAllGuestBeds['freeBeds_' + dateStr + 'uID' + window.autid] === undefined) {
                isEnabled = true;
            } else {
                isEnabled = false;
            }
        }

        if (isEdit) {
            oB = window.newUserRes['user_Res_Dates_' + dateStr];
            if (isNaN(oB)) {
                oB = 0;
            }
            hasFreeBeds = (occupied - oB < numBeds);
        } else {
            if (str[0] === 'WO') {
                $('.priority>li>.GU-datepicker-content').html(window.allClans['GU']).css({color: '#333'})
            } else {
                $('.priority>li>.WO-datepicker-content').html(window.allClans['WO']).css({color: '#333'})
            }
            priority.html(window.reservationStrings.prior + ': ' + str[1]).css({color: '#b7282e'});
        }
        if (!hasFreeBeds) {
            isEnabled = false;
        }
        returnObject = {
            enabled: isEnabled,
            tooltip: str[1],
            classes: bothClasses + 'pID_' + str[2] + ownClass,
            content: '<div class="datepicker-occupied-beds">' + occupiedBeds + '</div><div class="datepicker-day-date">' + d.getDate() + '</div>'
        };
        return returnObject;
    },
    addBeforeShowDayNew: function (d, otherClanDate, isEdit) {
        let numBeds = window.settings.setting_num_bed,
            dateStr = d.getFullYear() + '_' + GlobalFunctions.smallerThenTen(d.getMonth()) + '_' + GlobalFunctions.smallerThenTen(d.getDate()),
            pickerDateStr = V3Reservation.formatDate(d, false, '_'),
            occupied = isNaN(parseInt(window.newAllGuestBeds['freeBeds_' + dateStr], 10)) ? 0 : parseInt(window.newAllGuestBeds['freeBeds_' + dateStr], 10),
            oB = 0,
            today = new Date(),
            otherClanMinDate = new Date(),
            otherClanMaxDate = new Date(d.getTime()),
            ownClass = (window.newUserRes.hasOwnProperty('user_Res_Dates_' + dateStr) && !isEdit) ? ' myRes' : '',
            occupiedBeds = (window.newAllGuestBeds['freeBeds_' + dateStr] === undefined) ? '<span class="freeB">' + numBeds + '</span><span class="occB">0</span>' : '<span class="freeB">' + (numBeds - occupied) + '</span><span class="occB">' + occupied + '</span>',
            str = (window.datePickerPeriods[pickerDateStr] !== undefined) ? window.datePickerPeriods[pickerDateStr].split('|') : '',
            priority = $('.priority>li>.' + str[0] + '-datepicker-content'),
            bothClasses = (str.length === 4) ? str[0] + '-datepicker-' + str[3] + ' ' : str[0] + '-datepicker-content ',
            isEnabled = false,
            hasFreeBeds = (occupied < numBeds),
            returnObject;
        today.setHours(0, 0, 0, 0);
        otherClanMinDate.setDate(today.getDate() + 10);
        otherClanMaxDate.setDate(d.getDate() + 10);
        if (d.getTime() >= today.getTime() &&
            window.newAllGuestBeds['freeBeds_' + dateStr + 'uID' + window.autid] === undefined) {
            isEnabled = true;
        }
        if (window.uID.clan_code !== str[0] && otherClanMaxDate > otherClanMinDate) {
            /**
             *
             * Reservieren immer erlauben!!! GV Entscheid über Sekundär-Belegungen 10. April 2021
             * ursprünglicher Wert: isEnabled = false;
             *
             */
            isEnabled = true;
        }
        if (isEdit) {
            oB = window.newUserRes['user_Res_Dates_' + dateStr];
            if (isNaN(oB)) {
                oB = 0;
            }
            hasFreeBeds = (occupied - oB < numBeds);
        } else {
            if (str[0] === 'WO') {
                $('.priority>li>.GU-datepicker-content').html(window.allClans['GU']).css({color: '#333'})
            } else {
                $('.priority>li>.WO-datepicker-content').html(window.allClans['WO']).css({color: '#333'})
            }
            priority.html(window.reservationStrings.prior + ': ' + str[1]).css({color: '#b7282e'});
        }
        if (!hasFreeBeds) {
            isEnabled = false;
        }
        // check if its needed in edit mode too
        if (str[3] === 'end') {
            isEnabled = true;
        }
        returnObject = {
            enabled: isEnabled,
            tooltip: str[1],
            classes: bothClasses + 'pID_' + str[2] + ownClass,
            content: '<div class="datepicker-occupied-beds">' + occupiedBeds + '</div><div class="datepicker-day-date">' + d.getDate() + '</div>'
        };
        return returnObject;
    },
    initNew: function (pID, startDate, fromPeriod) {
        V3Reservation.periodID = pID;
        V3Reservation.fromPeriod = fromPeriod;
        let period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID)),
            today = new Date(),
            guestDateEl,
            priority,
            priorityContent,
            otherClanDate = new Date();
        otherClanDate.setDate(otherClanDate.getDate() + 10);
        otherClanDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        if (period === null) {
            V3Reservation.writeLocalStorage(window.periods);
            period = $.parseJSON(localStorage.getItem('period_' + V3Reservation.periodID));
        }
        $('#periodID').val(V3Reservation.periodID);
        if (startDate < today && !V3Reservation.fromPeriod) {
            startDate = today;
        }
        priority = $('.priority>li>.' + period.clan_code + '-datepicker-content');
        priorityContent = priority.html();
        let pEnd = period.period_end.replace(' ', 'T');
        V3Reservation.periodEndDate = new Date(pEnd);
        pEnd = window.settings.setting_calendar_start.replace(' ', 'T');
        window.endDate = new Date(pEnd);
        startDate.setHours(0, 0, 0, 0);
        window.endDate.setHours(0, 0, 0, 0);
        if (V3Reservation.fromPeriod) {
            window.endDate = V3Reservation.periodEndDate;
        }
        window.endDate.setFullYear(window.endDate.getFullYear() + parseInt(window.settings.setting_calendar_duration));
        $('#hideAll').hide();
        $('[id^="show_res"]').show();
        priority.html(window.reservationStrings.prior + ': ' + priorityContent).css({color: '#b7282e !important'});
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
            beforeShowDay: function (Date) {
                return V3Reservation.addBeforeShowDayNew(Date, otherClanDate, false);
            },
            orientation: 'center'
        };

        $('.input-daterange').datepicker(V3Reservation.datePickerSettings).on('hide', function (e) {
            let formatDateStr = V3Reservation.formatDate(e.date, false, '_'),
             str;
            if (!formatDateStr) {
                V3Reservation.giveFocus();
                V3Reservation.noClick(true);
                return false;
            }
            str = window.datePickerPeriods[formatDateStr].split('|');
            $('#periodID').val(str[2]);
            V3Reservation.onHide(e);
        });
        window.resStartPicker = $('.input-daterange').find('#reservation_started_at');
        window.resEndPicker = $('.input-daterange').find('#reservation_ended_at');
        if (V3Reservation.fromPeriod) {
            window.resStartPicker.datepicker('setDate', startDate)
            let dates = {
                startDate: startDate,
                endDate: new Date(V3Reservation.periodEndDate.getTime()),
            };
            V3Reservation.adaptChanged(dates, dates.endDate);
            window.resEndPicker.datepicker('setDate', null);
            V3Reservation.noClick(false);
        }
        guestDateEl = $('#guestDates_' + 0);
        guestDateEl.datepicker(V3Reservation.datePickerSettings);
        window.startGuestPicker[0] = guestDateEl.find('#reservation_guest_started_at_' + 0);
        window.endGuestPicker[0] = guestDateEl.find('#reservation_guest_ended_at_' + 0);
        V3Reservation.getFreeBeds(window.resStartPicker.datepicker('getStartDate'), window.resEndPicker.datepicker('getStartDate'), 'freeBeds_');
    },
    initEdit: function (pID, startDate, fromPeriod) {
        V3Reservation.periodID = pID;
        let period = window.userPeriod,
            today = new Date(),
            guestDateEl,
        otherClanDate = new Date();
        otherClanDate.setDate(otherClanDate.getDate() + 10);
        otherClanDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        $('#periodID').val(V3Reservation.periodID);
        if (startDate < today && localStorage.getItem('new_res') !== '0') {
            startDate = today;
        }
        V3Reservation.periodEndDate = new Date(period.period_end);
        window.endDate = new Date(window.settings.setting_calendar_start);
        if (localStorage.getItem('new_res') === '0') {
            window.endDate = new Date(window.endDateString[0], (window.endDateString[1] - 1), window.endDateString[2], 0, 0, 0);
        }
        startDate.setHours(0, 0, 0, 0);
        window.endDate.setHours(0, 0, 0, 0);
        $('#hideAll').hide();
        $('[id^="show_res"]').show();
        V3Reservation.datePickerSettings = {
            format: "dd.mm.yyyy",
            weekStart: 1,
            todayBtn: "linked",
            clearBtn: true,
            language: 'de',
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true,
            startDate: V3Reservation.formatDate(startDate),
            endDate: V3Reservation.formatDate(window.endDate),
            defaultViewDate: {
                year: today.getFullYear(),
                month: today.getMonth(),
                day: today.getDate()
            },
            beforeShowDay: function (Date) {
                return V3Reservation.addBeforeShowDayEdit(Date, otherClanDate, true, this.startDate);
            },
            orientation: 'center'
        };
        $('.input-daterange').datepicker(V3Reservation.datePickerSettings).on('hide', function (e) {
            V3Reservation.onHide(e);
        });
        window.resStartPicker = $('.input-daterange').find('#reservation_started_at');
        window.resEndPicker = $('.input-daterange').find('#reservation_ended_at');
        if (fromPeriod) {
            window.resStartPicker.datepicker('setDate', startDate)
            let dates = {
                startDate: startDate,
                endDate: new Date(startDate.getTime()),
            };
            V3Reservation.adaptChanged(dates, dates.endDate);
        }
        if (localStorage.getItem('new_res') === '0') {
            let dates = {
                startDate: startDate,
                endDate: new Date(startDate.getTime()),
            };
            $.each($('[id^="guestDates_"]'), function (i, n) {
                guestDateEl = $('#guestDates_' + i);
                guestDateEl.datepicker(V3Reservation.datePickerSettings);
                window.startGuestPicker[i] = guestDateEl.find('#reservation_guest_started_at_' + i);
                window.endGuestPicker[i] = guestDateEl.find('#reservation_guest_ended_at_' + i);
                window.startGuestPicker[i].datepicker('setStartDate', window.resStartPicker.datepicker('getDate'));
                window.startGuestPicker[i].datepicker('setEndDate', window.resEndPicker.datepicker('getDate'));
                window.endGuestPicker[i].datepicker('setStartDate', window.resStartPicker.datepicker('getDate'));
                window.endGuestPicker[i].datepicker('setEndDate', window.resEndPicker.datepicker('getDate'));
              //  V3Reservation.adaptChanged(dates, dates.endDate, true);
            });
        } else {
            guestDateEl = $('#guestDates_' + 0);
            guestDateEl.datepicker(V3Reservation.datePickerSettings);
            window.startGuestPicker[0] = guestDateEl.find('#reservation_guest_started_at_' + 0);
            window.endGuestPicker[0] = guestDateEl.find('#reservation_guest_ended_at_' + 0);
        }
        V3Reservation.getFreeBeds(window.resStartPicker.datepicker('getStartDate'), window.resEndPicker.datepicker('getEndDate'), 'freeBeds_');
    },
    adaptChanged: function (dates, periodEndDate) {
        if (isNaN(dates.endDate.getTime()) || isNaN(dates.startDate.getTime())) {
            window.resEndPicker.datepicker('setDate', null);
            window.resStartPicker.datepicker('setDate', null);
            for (let i = 0; i < window.startGuestPicker.length; i++) {
                window.startGuestPicker[i].datepicker('setDate', null);
                window.endGuestPicker[i].datepicker('setDate', null);
            }
            V3Reservation.noClick(true);
            return false;
        }
        if (dates.startDate >= dates.endDate) {
            dates.endDate.setDate(dates.endDate.getDate() + 1);
            window.resEndPicker.datepicker('setStartDate', dates.endDate);
            window.resEndPicker.datepicker('setEndDate', periodEndDate);
            window.resStartPicker.datepicker('setEndDate', periodEndDate);
            window.resEndPicker.datepicker('setDate', dates.endDate);
            V3Reservation.noClick(false);
        }
        for (let i = 0; i < window.startGuestPicker.length; i++) {
            if (dates.startDate > window.startGuestPicker[i].datepicker('getDate')) {
                window.startGuestPicker[i].datepicker('setDate', dates.startDate);
            }
            if (dates.endDate <= window.endGuestPicker[i].datepicker('getDate')) {
                window.endGuestPicker[i].datepicker('setDate', dates.endDate);
            }
            if (dates.startDate >= window.endGuestPicker[i].datepicker('getDate')) {
                let start = new Date(dates.startDate.getTime());
                start.setDate(dates.startDate.getDate() + 1);
                start.setHours(0, 0, 0, 0);
                window.endGuestPicker[i].datepicker('setDate', start);
                window.endGuestPicker[i].datepicker('setEndDate', start);
            } else {
                window.endGuestPicker[i].datepicker('setEndDate', dates.endDate);
            }
            window.startGuestPicker[i].datepicker('setStartDate', dates.startDate);
            window.startGuestPicker[i].datepicker('setEndDate', dates.endDate);
            if (V3Reservation.firstTimeChangeEndDate === 1) {
                window.endGuestPicker[i].datepicker('setDate', dates.endDate);
                window.endGuestPicker[i].datepicker('setEndDate', dates.endDate);
            }
            if (window.startGuestPicker[i].datepicker('getDate') === null) {
                window.startGuestPicker[i].datepicker('setDate', window.resStartPicker.datepicker('getDate'));
            }
            if (window.endGuestPicker[i].datepicker('getDate') === null) {
                window.endGuestPicker[i].datepicker('setDate', window.resEndPicker.datepicker('getDate'));
            }
        }
        V3Reservation.calcNights(dates.startDate, dates.endDate, '#reservation_nights_total');
        V3Reservation.setReservationHeaderText('#res_header_text', dates, $('#reservation_nights_total').text())
        V3Reservation.getFreeBeds(dates.startDate, dates.endDate, 'freeBeds_');
        if (window.route.indexOf('edit_reservation') > -1) {
            $.each($('[id^="reservation_guest_guests_"]'), function (i, n) {
                $('#reservation_guest_guests_' + i).change();
            })
        }
    },
    getFreeBeds: function (start, end, prefix) {
        let reservations = window.reservationsSums;
        V3Reservation.writeFreeBedsStorage(reservations, prefix, start, end);
        if (Object.keys(reservations).length === 0 && reservations.constructor === Object) {
        }
    },
    writeFreeBedsStorage: function (objects, prefix, start, end) {
        let len = (Object.keys(objects).length)
        Object.keys(objects)
            .forEach(function (key) {
                let c,
                    test = new RegExp('^' + prefix),
                    free = window.settings.setting_num_bed;
                if (test.test(key)) {
                    c = key.split('_');
                    // total - (occupied + 1)
                    if (window.parseInt(objects[key], 10) > 0) {
                        free -= window.parseInt(objects[key], 10)
                        window.localStorage.setItem(c[1] + '-' + c[2] + '-' + c[3], free);
                        V3Reservation.freeBeds[c[1] + '-' + c[2] + '-' + c[3]] = free;
                    }
                }
            });
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
            let checkDateStr = start.getFullYear() + '-' + GlobalFunctions.smallerThenTen(start.getMonth()) + '-' + GlobalFunctions.smallerThenTen(start.getDate());
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
                    monthStr = st.getFullYear() + '-' + GlobalFunctions.smallerThenTen(st.getMonth() + 1);
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
            monthStr = start.getFullYear() + '-' + GlobalFunctions.smallerThenTen(start.getMonth() + 1);
            tl.append('<li id="tl-' + monthStr + '" class="graph-unit-container cal-unit-container">' +
                '<div class="graph-unit-day cal-unit-day">' + GlobalFunctions.showDate(start, 'short') + '</div></li>');
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
        if (d === undefined) return false;
        if (separator === undefined) {
            separator = '.';
        }
        if (typeof d === 'string') {
            return d;
        }
        if (!d instanceof Date) {
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
    },
    setGuestHeaderText: function (id, dates, guestKind, numberGuest, numberNight) {
        let guest_kind = (guestKind == '0') ? '' : '&nbsp;x&nbsp;' + window.rolesTrans[guestKind],
            nights = (parseInt(numberNight, 10) === 1) ? window.reservationStrings.night : window.reservationStrings.nights,
            num_guest = (numberGuest == '') ? '' : ': ' + numberGuest,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' ' + nights,
            dateString = window.guestTitle + V3Reservation.formatDate(dates.startDate) + ' - ' + V3Reservation.formatDate(dates.endDate) + numNight + num_guest + guest_kind;
        $('#guest_title_' + id).html(dateString);
        $('#hidden_guest_title_' + id).val(dateString);
    },
    setReservationHeaderText: function (id, dates, numberNight) {
        let nights = (parseInt(numberNight, 10) === 1) ? window.reservationStrings.night : window.reservationStrings.nights,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' ' + nights,
            dateString = V3Reservation.formatDate(dates.startDate) + ' - ' + V3Reservation.formatDate(dates.endDate) + numNight;
        $('#res_header_text').html(dateString);
        $('#reservation_title').val(dateString);
    },
    setFreeBeds: function (start, end) {
        $('[id^="free-beds_"]').html('');
        let fillNewFreeBeds = function (str) {
            let beds = window.localStorage.getItem(str),
                bedNumberShow = (beds === null) ? window.settings.setting_num_bed : GlobalFunctions.smallerThenTen(window.parseInt(beds, 10), true),
                showBedDateStr = str.split('-'),
                dateBed = new Date(showBedDateStr[0], showBedDateStr[1], showBedDateStr[2], 0, 0, 0, 0),
                bedString = showBedDateStr[2] + '.' + GlobalFunctions.smallerThenTen((parseInt(showBedDateStr[1], 10) + 1)) + '.' + showBedDateStr[0] + ': <span style="text-align: right"><strong>' + bedNumberShow + '</strong></span>';

            $('#all-free-beds-standard').html('');
            if ($('#free-beds_' + str).length > 0) {
                $('#free-beds_' + str).html(bedString);
            } else {
                $('#all-free-beds').append('<li id="free-beds_' + str + '">' + bedString + '</li>');
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
            $('#hidden_reservation_guest_price_' + id).val(price);
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
            editRes = (window.localStorage.getItem('new_res') === '0') ? 'resId' + $('#id').val() : '',
            totalBeds = window.settings.setting_num_bed,
            checkAvailableBeds = function (str) {
                let checkBedStorage = parseInt(window.localStorage.getItem(str), 10);
                if (isNaN(checkBedStorage)) {
                    checkBedStorage = totalBeds;
                }
                if (editRes !== '') {
                    if (checkBedStorage < total && !tooMuch) {
                        tooMuch = true;
                        V3Reservation.tooMuchBeds(str);
                        return false;
                    }
                } else {
                    if ((total > checkBedStorage) && !tooMuch) {
                        tooMuch = true;
                        V3Reservation.tooMuchBeds(str);

                        return false;
                    }
                }
            };
        if (!tooMuch) {
            V3Reservation.loopDates($('#reservation_started_at').val(), $('#reservation_ended_at').val(), '-', checkAvailableBeds, V3Reservation.tooMuch);
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
            checkDateStr = start.getFullYear() + separator + GlobalFunctions.smallerThenTen(start.getMonth()) + separator + GlobalFunctions.smallerThenTen(start.getDate());
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
        $('#free_beds-modal').modal({
            backdrop: false,
            keyboard: true
        });
        if (idBeds.length > 0) {
            $('#all-free-beds').scrollTop($('#all-free-beds').scrollTop() + (idBeds.position().top - 141));
        }
        $('#total_res')
            .addClass('alert-danger')
            .removeClass('alert-info');
        $('#save_reservation').attr('disabled', true);
        $('#clone_guest').attr('disabled', true);
        $('#show-all-free-beds>.hideContent').addClass('showContent');
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
    getOtherReservations: function (s, e) {
        $.ajax({
            url: 'check_others',
            method: 'POST',
            data: {
                start: s,
                end: e,
                "_token": window.token,
            },
            success: function (data) {
               let res = $.parseJSON(data),
               str = '';
               $.each(res, function () {
                   str += '<li>' + this.reservation_started_at + '-' + this.reservation_ended_at + ':<br>' +
                        '<a href="' + window.urlTo + '/user/profile/' + this.user_id + '">' + this.user_name + '</a><br>' +
                        '<a href="mailto:' + this.user_mail + '">' + this.user_mail + '</a><br>' +
                        '<a href="tel:' + this.user_fon + '">' + this.user_fon + '</a><br>' +
                       '</li>';
               })
                if (str.length === 0) {
                    $('#user-res').html('<li>Keine anderen Reservierungen</li>');
                }else {
                    $('#user-res').html(str);
                }
            }
        });
    },
    checkExistentReservation: function (s, e) {
        let start = s.getFullYear() + '-' + GlobalFunctions.smallerThenTen((s.getMonth() + 1)) + '-' + GlobalFunctions.smallerThenTen(s.getDate()),
            end = e.getFullYear() + '-' + GlobalFunctions.smallerThenTen((e.getMonth() + 1)) + '-' + GlobalFunctions.smallerThenTen(e.getDate());
        $.ajax({
            url: 'check_existent',
            method: 'POST',
            data: {
                start: start,
                end: end,
                "_token": window.token,
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                if (!isNaN(parseInt(data, 10))) {
                    $('#edit_reservation_exists').attr('action', window.urlTo + '/edit_reservation/' + data);
                    $('#reservation_exists').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                V3Reservation.getOtherReservations(start, end);
            }
        })
    },
    deleteReservation: function (id) {
        $.ajax({
            url: '/delete_reservation',
            method: 'POST',
            data: {
                res_id: id,
                "_token": window.token,
            },
            success: function (data) {
                GlobalFunctions.unAuthorized(data);
                let d = $.parseJSON(data);
                if (d.hasOwnProperty('error')) {
                    $('#no_delete_reservation').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    return false;
                }
                $('#delete_table_all_reservations_' + id).remove();
                $('#delete_table_all_reservations_guests_' + id).remove();
                if ($('[id^="all_reservations_"]').length === 0) {
                    $('#noview').find('h1').html(window.reservationStrings.no_bookings);
                }
            }
        })
    },
    deleteGuestEntry: function (id) {
        let dates = {};
        $('#guests_date_' + id).remove();
        if ($('[id^="guests_date_"]').length === 0) {
            $('#addZeroGuest').show();
            $('#save_reservation').attr('disabled', false);
        }
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
            V3Reservation.calcAllPrices();
            V3Reservation.calcGuests();
        }
        if (window.startGuestPicker.length === 0) {
            dates.startDate = window.resStartPicker.datepicker('getDate');
            dates.endDate = window.resEndPicker.datepicker('getDate');
            V3Reservation.calcNights(dates.startDate, dates.endDate, '#reservation_nights_total');
            V3Reservation.setReservationHeaderText('#res_header_text', dates, $('#reservation_nights_total').text())
            V3Reservation.calcAllPrices();
            V3Reservation.calcGuests();
        }
        $('[id^="clone_guest"]').attr('disabled', false);
        $('[id^="delete_reservation_"]').attr('disabled', true);
        $('#total_res').removeClass('alert-danger').addClass('alert-info');
    }
};
