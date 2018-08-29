var Reservation = {
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
            for (var i = 0; i < window.reservations.length; i++) {
                var res = window.reservations[i];
                Object.keys(res)
                    .forEach(function (key) {
                        var c;
                        if (/^occupiedBeds_/.test(key)) {
                            c = key.split('_');
                            window.localStorage.setItem(c[1] + '-' + c[2] + '-' + c[3], res[key]);
                            Reservation.occupiedBeds[c[1] + '-' + c[2] + '-' + c[3]] = res[key];
                        }
                    });
            }
            Reservation.setOccupiedBeds(start, end);
        }
    },
    writeLocalStorage: function (data) {
        var d, i;
        for(i = 0; i < data.length; i++) {
            d = data[i];
            if (!window.localStorage.hasOwnProperty('period_' + i)) {
                window.localStorage.setItem('period_' + d.id, JSON.stringify(d));
            }
        }
    },
    createTimeLine: function (periods) {
        var s = window.settings.setting_calendar_start.split(' ')[0].split('-'),
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
                var tt = findP(periods, 'period_start_new', monthStr, start);
                var st = new Date(start);
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
            tl.append('<li id="tl-' + monthStr + '" class="graph-unit-container cal-unit-container"><div class="graph-unit-day cal-unit-day">' + window.showDate(start, 'short') + '</div></li>');
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
            s = new Date(Reservation.deFormatDate(s, '.', true))
        }
        if (typeof e !== 'object') {
            e = new Date(Reservation.deFormatDate(e, '.', true))
        }
        var nights = parseInt(((e - s) / 1000 / 24 / 3600), 10),
        showNights = (nights < 0) ? 0 : nights;
        $(el).text(showNights);
        $('[id^="number_nights_"]').trigger('input');
        $('[id^="price_"]').trigger('input');
    },
    formatDate: function (d, long) {
        if (typeof d === 'string') {
            return d;
        }
        var jsMonth = d.getMonth() + 1,
            day = (d.getDate() < 10) ? '0' + d.getDate() : d.getDate(),
            month = (jsMonth < 10) ? '0' + jsMonth : jsMonth;
        if (long) {
            return d.getDate() + '. ' + window.fullMonthNames[d.getMonth()] + ' ' + d.getFullYear();
        }
        return day + '.' + month + '.' + d.getFullYear();
    },
    deFormatDate: function (d, sep, asString) {
        var tmp = d.split(sep),
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
        var i,
        s = (typeof start !== 'object') ? new Date(Reservation.deFormatDate(start, '.', true)) : start,
        e = (typeof end !== 'object') ? new Date(Reservation.deFormatDate(end, '.', true)) : end,
        gs,
        ge;
        $.each($('[id^="reservation_guest_started_at_"]'), function (i, n) {
            gs = new Date(Reservation.deFormatDate($(n).val(), '.', true));
            ge = new Date(Reservation.deFormatDate($('#reservation_guest_ended_at_' + i).val(), '.', true));
            if (s > gs || onInit) {
                anyPickerStart[i + 1].setSelectedDate(s);
                anyPickerStart[i + 1].setMinimumDate(s);
                anyPickerStart[i + 1].setting.i18n.headerTitle = Reservation.langStrings.arrival + ': ' + Reservation.formatDate(s, true)
            }
            if (e < ge || onInit) {
                anyPickerEnd[i + 1].setSelectedDate(e);
                anyPickerEnd[i + 1].setMaximumDate(e);
                anyPickerEnd[i + 1].setting.i18n.headerTitle = Reservation.langStrings.depart + ': ' + Reservation.formatDate(e, true)
            }
            Reservation.calcNights(start, end, '#number_nights_' + i);
        });
        Reservation.calcNights(start, end, '#reservation_nights_total');
    },
    setGuestHeaderText: function (id, dates, guestKind, numberGuest, numberNight) {
        var guest_kind = (guestKind == '0') ? '' : '&nbsp;x&nbsp;' + window.rolesTrans[guestKind],
            num_guest = (numberGuest == '') ? '' : ': ' + numberGuest,
            numNight = (numberNight == '') ? '' : ' = ' + numberNight + ' Nächte';
        $('#guest_title_' + id).html(window.guestTitle + dates.start + ' - ' + dates.end + numNight + num_guest + guest_kind);
    },
    setOccupiedBeds: function (start, end) {
        var i,
            el = $('#all-free-beds_text'),
            beds = Reservation.occupiedBeds,
            bedKey,
            bedDateString,
            bedNumberShow,
            bedDateFormated,
            dateBed;
        el.html('');
        for (i = 0; i < Object.keys(beds).length; i++) {
            bedKey = Object.keys(beds)[i];
            bedDateString = bedKey.split('-');
            dateBed = new Date(bedDateString[0], bedDateString[1], bedDateString[2], 0, 0, 0);
            if (dateBed >= start && dateBed <= end) {
                bedNumberShow = (window.settings.setting_num_bed - beds[bedKey] === 0) ? '-' : (window.settings.setting_num_bed - beds[bedKey]);
                bedDateFormated = Reservation.formatDate(dateBed);
                el.append('<div id="#free-beds_' + bedKey + '">' + bedDateFormated + ': ' + bedNumberShow + '</div>')
                if (parseInt(bedNumberShow, 10) >= window.settings.setting_num_bed && !Reservation.disabledDates.find(x => x.val === bedDateFormated)) {
                    Reservation.disabledDates.push(
                        {
                            val: bedDateFormated
                        }
                    )
                }
            }
        }
        console.log(Reservation.disabledDates);
        $('#free_beds')
            .show();
    },
    createIOSDatePicker: function (els, start, end, defStart, defEnd, periodID) {
        var sEndD,
            sStartD,
            id,
            selectedStartDate = start,
            selectedEndDate = end,
            period = $.parseJSON(window.localStorage.getItem('period_' + periodID)),
            minStart = (defStart === null) ? new Date(period.period_start) : defStart,
            maxEnd = (defEnd === null) ? new Date(period.period_end) : defEnd;
        $(els[0]).AnyPicker({
            mode: 'datetime',
            inputDateTimeFormat: Reservation.globalDateFormat,
            dateTimeFormat: Reservation.globalDateFormat,
            lang: 'de-ch',
            headerTitle: {
                markup: "<span class='ap-header__title'></span>",
                type: "Text",
                contentBehaviour: "Static",
            },
            i18n: {
                setButton: 'Auswählen',
                cancelButton: 'Abbrechen',
                headerTitle: Reservation.langStrings.arrival + ': ' + Reservation.formatDate(start, true)
            },
            inputChangeEvent: "onChange",
            disableValues: {
                date: Reservation.disabledDates
            },
            onInit: function()
            {
                id = this.elem.id.split('_')[4];
                Reservation.datePickerStart = this;
                sEndD = Reservation.datePickerStart.formatOutputDates(end, Reservation.globalDateFormat);
                Reservation.datePickerStart.setMinimumDate(Reservation.datePickerStart.formatOutputDates(minStart, Reservation.globalDateFormat));
                Reservation.datePickerStart.setMaximumDate(sEndD);
                Reservation.datePickerStart.setSelectedDate(start);
                Reservation.calcNights(start, end, els[2]);
            },
            onChange: function(cIndex, rIndex, oSelectedValues)
            {
                Reservation.setOccupiedBeds(oSelectedValues.date, Reservation.datePickerEnd.tmp.selectedDate);
                this.parseDisableValues();
                $(this.tmp.overlaySelector).find('.ap-header__title').text(Reservation.langStrings.arrival + ': ' + Reservation.formatDate(oSelectedValues.date, true))
            },
            onSetOutput: function(sOutput, oSelectedValues)
            {
                id = this.elem.id.split('_')[4];
                sStartD = sOutput;
                selectedStartDate = oSelectedValues.date;
                Reservation.datePickerEnd.setMinimumDate(sStartD);
                this.setting.i18n.headerTitle = Reservation.langStrings.arrival + ': ' + Reservation.formatDate(selectedStartDate, true);
                if (this.elem.id.indexOf('guest') === -1) {
                    Reservation.checkReservationMaxDates(selectedStartDate, selectedEndDate, window.datePickersStart, window.datePickersEnd);
                }
                Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $('#reservation_guest_guests_' + id).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
                Reservation.calcNights(selectedStartDate, selectedEndDate, els[2]);
                Reservation.calcAllPrices();
            }
        });
        $(els[1]).AnyPicker({
            mode: 'datetime',
            inputDateTimeFormat: Reservation.globalDateFormat,
            dateTimeFormat: Reservation.globalDateFormat,
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
                headerTitle: Reservation.langStrings.depart + ': ' + Reservation.formatDate(end, true)
            },
            inputChangeEvent: "onChange",
            disableValues: {
                date: Reservation.disabledDates
            },
            onInit: function()
            {
                id = this.elem.id.split('_')[4];
                Reservation.datePickerEnd = this;
                sStartD = Reservation.datePickerEnd.formatOutputDates(start);
                sEndD = Reservation.datePickerEnd.formatOutputDates(maxEnd, Reservation.globalDateFormat);
                Reservation.datePickerEnd.setMinimumDate(sStartD);
                Reservation.datePickerEnd.setMaximumDate(sEndD);
                Reservation.datePickerEnd.setSelectedDate(end);
            },
            onChange: function(cIndex, rIndex, oSelectedValues)
            {
                Reservation.setOccupiedBeds(Reservation.datePickerStart.tmp.selectedDate, oSelectedValues.date);
                this.parseDisableValues();
                $(this.tmp.overlaySelector).find('.ap-header__title').text(Reservation.langStrings.depart + ': ' + Reservation.formatDate(oSelectedValues.date, true))
            },
            onSetOutput: function(sOutput, oSelectedValues)
            {
                id = this.elem.id.split('_')[4];
                sEndD = sOutput;
                selectedEndDate = oSelectedValues.date;
                Reservation.datePickerStart.setMaximumDate(sEndD);
                this.setting.i18n.headerTitle = Reservation.langStrings.depart + ': ' + Reservation.formatDate(selectedEndDate, true);
                if (this.elem.id.indexOf('guest') === -1) {
                    Reservation.checkReservationMaxDates(selectedStartDate, selectedEndDate, window.datePickersStart, window.datePickersEnd);
                }
                Reservation.setGuestHeaderText(id, {start: $('#reservation_guest_started_at_' + id).val(), end: $('#reservation_guest_ended_at_' + id).val()}, $('#reservation_guest_guests_' + id).val(), $('#reservation_guest_num_' + id).val(), $('#number_nights_' + id).text());
                Reservation.calcNights(selectedStartDate, selectedEndDate, els[2]);
                Reservation.calcAllPrices();
            }
        });
        window.datePickersStart.push(Reservation.datePickerStart);
        window.datePickersEnd.push(Reservation.datePickerEnd);
    },
    calcGuests: function () {
        var i,
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
        return total;
    },
    calcPrice: function (id) {
        var guestGuest = $('#reservation_guest_guests_' + id).val(),
            guestNight = parseInt($('#number_nights_' + id).text(), 10),
            guestNum = parseInt($('#reservation_guest_num_' + id).val(), 10),
            price = (window.rolesTaxes[guestGuest] * guestNight * guestNum).toFixed(2);
        if (!isNaN(price)) {
            $('#price_' + id).text(price);
            $('#hidden_price_' + id).val(price);
            $('[id^="price_"]').trigger('input');
        }
        Reservation.calcGuests();
        return parseFloat(price);
    },
    calcAllPrices: function () {
        var total = 0,
            prices = $('[id^="hider_"]');
        $.each(prices, function (i, n) {
            total += Reservation.calcPrice(i);
        });
        $('#reservation_costs_total').text(total.toFixed(2));
        $('#hidden_reservation_costs_total').val(total.toFixed(2));
    }
};