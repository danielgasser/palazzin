let V3Reservation = {
    allPeriods: {},
    periodID: null,
    globalDateFormat: 'dd.MM.yyyy',
    tmpDate: new Date(),
    occupiedBeds: {},
    tmpDatePickerInstance: {},
    disabledDates: [],
    langStrings: window.reservationStrings,
    getOccupiedBeds: function (start, end) {
        if (window.reservations.length > 0) {
            for (let i = 0; i < window.reservations.length; i++) {
                let res = window.reservations[i];
                Object.keys(res)
                    .forEach(function (key) {
                        let c;
                        if (/^occupiedBeds_/.test(key)) {
                            c = key.split('_');
                            window.localStorage.setItem(c[1] + '-' + c[2] + '-' + c[3], res[key]);
                            V3Reservation.occupiedBeds[c[1] + '-' + c[2] + '-' + c[3]] = res[key];
                        }
                    });
            }
            V3Reservation.setOccupiedBeds(start, end);
        }
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
    calcNights: function (s, e, el) {
        if (typeof s !== 'object') {
            s = new Date(V3Reservation.deFormatDate(s, '.', true))
        }
        if (typeof e !== 'object') {
            e = new Date(V3Reservation.deFormatDate(e, '.', true))
        }
        let nights = parseInt(((e - s) / 1000 / 24 / 3600), 10),
        showNights = (nights < 0) ? 0 : nights;
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
        let s = (typeof start !== 'object') ? new Date(V3Reservation.deFormatDate(start, '.', true)) : start,
        e = (typeof end !== 'object') ? new Date(V3Reservation.deFormatDate(end, '.', true)) : end,
        gs,
        ge;
        $.each($('[id^="reservation_guest_started_at_"]'), function (i, n) {
            gs = new Date(V3Reservation.deFormatDate($(n).val(), '.', true));
            ge = new Date(V3Reservation.deFormatDate($('#reservation_guest_ended_at_' + i).val(), '.', true));
            if (s > gs || onInit) {
                anyPickerStart[i + 1].setSelectedDate(s);
                anyPickerStart[i + 1].setMinimumDate(s);
                anyPickerStart[i + 1].setting.i18n.headerTitle = V3Reservation.langStrings.arrival + ': ' + V3Reservation.formatDate(s, true)
            }
            if (e < ge || onInit) {
                anyPickerEnd[i + 1].setSelectedDate(e);
                anyPickerEnd[i + 1].setMaximumDate(e);
                anyPickerEnd[i + 1].setting.i18n.headerTitle = V3Reservation.langStrings.depart + ': ' + V3Reservation.formatDate(e, true)
            }
            V3Reservation.calcNights(start, end, '#number_nights_' + i);
        });
        V3Reservation.calcNights(start, end, '#reservation_nights_total');
    },
    setGuestHeaderText: function (id, dates, guestKind, numberGuest, numberNight) {
        let guest_kind = (guestKind == '0') ? '' : '&nbsp;x&nbsp;' + window.rolesTrans[guestKind],
            num_guest = (numberGuest == '') ? '' : ': ' + numberGuest,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' Nächte';
        $('#guest_title_' + id).html(window.guestTitle + dates.start + ' - ' + dates.end + numNight + num_guest + guest_kind);
    },
    setOccupiedBeds: function (start, end) {
        let fillNewOccupiedBeds = function (str) {
            let beds = window.localStorage.getItem(str),
                bedNumberShow = (window.settings.setting_num_bed - beds === '0' || beds === null) ? window.settings.setting_num_bed : window.smallerThenTen((window.settings.setting_num_bed - window.parseInt(beds, 10)), true),
                showBedDateStr = str.split('-'),
                dateBed = new Date(showBedDateStr[0], showBedDateStr[1], showBedDateStr[2], 0, 0, 0, 0);
            $('#all-free-beds_text').append('<div id="free-beds_' + str + '">' + showBedDateStr[2] + '.' + window.smallerThenTen((parseInt(showBedDateStr[1], 10) + 1)) + '.' + showBedDateStr[0] + ': <span style="text-align: right"><strong>' + bedNumberShow + '</strong></span></div>');
            if (parseInt(bedNumberShow, 10) <= 0 && !V3Reservation.disabledDates.find(x => x.val === V3Reservation.formatDate(dateBed))) {
                V3Reservation.disabledDates.push(
                    {
                        val: dateBed
                    }
                )
            }};
        V3Reservation.loopDates(start, end, '-', fillNewOccupiedBeds);
        /*
        for (i = 0; i < Object.keys(beds).length; i++) {
            bedKey = Object.keys(beds)[i];
            bedDateString = bedKey.split('-');
            dateBed = new Date(bedDateString[0], bedDateString[1], bedDateString[2], 0, 0, 0);
            if (dateBed >= start && dateBed <= end) {
                bedNumberShow = (window.settings.setting_num_bed - beds[bedKey] === 0) ? '-' : (window.settings.setting_num_bed - beds[bedKey]);
                bedDateFormated = V3Reservation.formatDate(dateBed);
                el.append('<div id="#free-beds_' + bedKey + '">' + bedDateFormated + ': ' + bedNumberShow + '</div>');
                if (parseInt(bedNumberShow, 10) >= window.settings.setting_num_bed && !V3Reservation.disabledDates.find(x => x.val === bedDateFormated)) {
                    V3Reservation.disabledDates.push(
                        {
                            val: bedDateFormated
                        }
                    )
                }
            }
        }
        */
        console.log(V3Reservation.disabledDates);
        $('#free_beds')
            .show();
    },
    createIOSDatePicker: function (els, start, end, periodID) {
        let sEndD,
            sStartD,
            id,
            today = new Date(),
            selectedStartDate = start,
            startEndDate = new Date(),
            selectedEndDate = end,
            period = $.parseJSON(window.localStorage.getItem('period_' + periodID)),
            periodStart = new Date(period.period_start),
            minStart,
            maxEnd;
        today.setHours(0, 0, 0, 0);
        minStart = (periodStart.getTime() < today) ? today : new Date(period.period_start);
        maxEnd = new Date(period.period_end);
        startEndDate.setDate(start.getDate() + 1);
        $(els[0]).AnyPicker({
            mode: 'datetime',
            inputDateTimeFormat: V3Reservation.globalDateFormat,
            dateTimeFormat: V3Reservation.globalDateFormat,
            lang: 'de-ch',
            headerTitle: {
                markup: "<span class='ap-header__title'></span>",
                type: "Text",
                contentBehaviour: "Static",
            },
            i18n: {
                setButton: 'Auswählen',
                cancelButton: 'Abbrechen',
                headerTitle: V3Reservation.langStrings.arrival + ': ' + V3Reservation.formatDate(start, true)
            },
            inputChangeEvent: "onChange",
            disableValues: {
                date: V3Reservation.disabledDates
            },
            onInit: function()
            {
                id = this.elem.id.split('_')[4];
                V3Reservation.datePickerStart = this;
                sEndD = V3Reservation.datePickerStart.formatOutputDates(end, V3Reservation.globalDateFormat);
                V3Reservation.datePickerStart.setMinimumDate(V3Reservation.datePickerStart.formatOutputDates(minStart, V3Reservation.globalDateFormat));
                V3Reservation.datePickerStart.setMaximumDate(sEndD);
                V3Reservation.datePickerStart.setSelectedDate(start);
                V3Reservation.calcNights(start, end, els[2]);
            },
            onChange: function(cIndex, rIndex, oSelectedValues)
            {
                V3Reservation.setOccupiedBeds(oSelectedValues.date, V3Reservation.datePickerEnd.tmp.selectedDate);
                this.parseDisableValues();
                $(this.tmp.overlaySelector).find('.ap-header__title').text(V3Reservation.langStrings.arrival + ': ' + V3Reservation.formatDate(oSelectedValues.date, true))
            },
            onSetOutput: function(sOutput, oSelectedValues)
            {
                id = this.elem.id.split('_')[4];
                sStartD = sOutput;
                selectedStartDate = oSelectedValues.date;
                V3Reservation.datePickerEnd.setMinimumDate(sStartD);
                this.setting.i18n.headerTitle = V3Reservation.langStrings.arrival + ': ' + V3Reservation.formatDate(selectedStartDate, true);
                if (this.elem.id.indexOf('guest') === -1) {
                    V3Reservation.checkReservationMaxDates(selectedStartDate, selectedEndDate, window.datePickersStart, window.datePickersEnd);
                }
                V3Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $('#reservation_guest_guests_' + id).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
                V3Reservation.calcNights(selectedStartDate, selectedEndDate, els[2]);
                V3Reservation.calcAllPrices();
            }
        });
        $(els[1]).AnyPicker({
            mode: 'datetime',
            inputDateTimeFormat: V3Reservation.globalDateFormat,
            dateTimeFormat: V3Reservation.globalDateFormat,
            lang: 'de-ch',
            headerTitle: {
                markup: "<span class='ap-header__title'></span>",
                type: "Text",
                contentBehaviour: "Static",
                format: ''

            },
            i18n: {
                setButton: 'Auswählen',
                cancelButton: 'Abbrechen',
                headerTitle: V3Reservation.langStrings.depart + ': ' + V3Reservation.formatDate(end, true)
            },
            inputChangeEvent: "onChange",
            disableValues: {
                date: V3Reservation.disabledDates
            },
            onInit: function()
            {
                id = this.elem.id.split('_')[4];
                V3Reservation.datePickerEnd = this;
                sStartD = V3Reservation.datePickerEnd.formatOutputDates(startEndDate);
                sEndD = V3Reservation.datePickerEnd.formatOutputDates(maxEnd, V3Reservation.globalDateFormat);
                V3Reservation.datePickerEnd.setMinimumDate(sStartD);
                V3Reservation.datePickerEnd.setMaximumDate(sEndD);
                V3Reservation.datePickerEnd.setSelectedDate(end);
            },
            onChange: function(cIndex, rIndex, oSelectedValues)
            {
                V3Reservation.setOccupiedBeds(V3Reservation.datePickerStart.tmp.selectedDate, oSelectedValues.date);
                this.parseDisableValues();
                $(this.tmp.overlaySelector).find('.ap-header__title').text(V3Reservation.langStrings.depart + ': ' + V3Reservation.formatDate(oSelectedValues.date, true))
            },
            onSetOutput: function(sOutput, oSelectedValues)
            {
                id = this.elem.id.split('_')[4];
                sEndD = sOutput;
                selectedEndDate = oSelectedValues.date;
                V3Reservation.datePickerStart.setMaximumDate(sEndD);
                this.setting.i18n.headerTitle = V3Reservation.langStrings.depart + ': ' + V3Reservation.formatDate(selectedEndDate, true);
                if (this.elem.id.indexOf('guest') === -1) {
                    V3Reservation.checkReservationMaxDates(selectedStartDate, selectedEndDate, window.datePickersStart, window.datePickersEnd);
                }
                V3Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $('#reservation_guest_guests_' + id).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
                V3Reservation.calcNights(selectedStartDate, selectedEndDate, els[2]);
                V3Reservation.calcAllPrices();
            }
        });
        window.datePickersStart.push(V3Reservation.datePickerStart);
        window.datePickersEnd.push(V3Reservation.datePickerEnd);
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
        $('#reservation_guest_num_total').text(total + ' +1');
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
        price = (window.rolesTaxes[guestGuest] * guestNight * guestNum).toFixed(2);
        if (!isNaN(price)) {
            $('#price_' + id).text(price);
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
        $('#reservation_costs_total').text(total.toFixed(2));
        $('#hidden_reservation_costs_total').val(total.toFixed(2));
    },
    checkOccupiedBeds: function (total) {
        let tooMuch = false,
            totalBeds = window.settings.setting_num_bed,
            checkAvailableBeds = function (str) {
            if (tooMuch) {}
                let checkBedStorage = parseInt(window.localStorage.getItem(str), 10),
                    availableBeds = totalBeds - checkBedStorage;
                if (total > availableBeds && !tooMuch) {
                    V3Reservation.tooMuchBeds(str, total);
                    tooMuch = true;
                }
            };
        V3Reservation.loopDates($('#reservation_started_at').val(), $('#reservation_ended_at').val(), '-', checkAvailableBeds, tooMuch);
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
    tooMuchBeds: function (date, bedNum) {
        let id = $('#free-beds_' + date);
        $.each($('[id^="free-beds_"]'), function (i, n) {
            $(n).removeClass('tooMuchBeds');
        });
        id.addClass('tooMuchBeds');
        $( '#free_beds').animate({
            right: '130px'
        }, 1);
        $('#all-free-beds').show();
        $('#hideAll').show();
        $('#all-free-beds').scrollTop($('#all-free-beds').scrollTop() + (id.position().top - 141));
        return false;
    }
};