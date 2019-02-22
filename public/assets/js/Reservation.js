var Reservation = {
    addedReservationCss: 'reservation-inter',
    firstAddedReservationCss: 'first-reservation',
    lastAddedReservationCss: 'last-reservation',
    instance: this,
    guestSum: 0,
    todayId: '#todaysDate_',
    startDate: window.localStorage.getItem('startDate'),
    endDate: window.localStorage.getItem('endDate'),
    cC: window.parseInt(window.localStorage.getItem('cC'), 10),
    cCounter: 0,
    disableSaving: function () {
        "use strict";
        $.each($('[id^="reservation_guest_guests_"]'), function (i, n) {
            if (n.value === '0') {
                $('#saveEditReserv').attr('disabled', true);
            }
        });
    },
    numberGuestForms: function () {
        "use strict";
        $.each($('[id^="guestFormID_"]'), function (i, n) {
            $(n).children('legend').children('span').text(' ' + (i + 1));
        });
    },
    toFixed: function (number, precision) {
        "use strict";
        var multiplier = Math.pow(10, precision + 1),
            wholeNumber = Math.floor(number * multiplier);
        return window.parseFloat(Math.round(wholeNumber / 10) * 10 / multiplier).toFixed(2);
    },
    adaptInputs: function (isGuest, editRes) {
        "use strict";
        var ss,
            lngFormat = 'dd.mm.yy',
            dateSeparator = '.',
            workDate,
            old_id,
            old_name,
            old_min,
            old_max,
            old_val,
            oldValsShow = [],
            oldValsO,
            oldVal,
            new_id,
            today = new Date(),
            opts = {
                day: 'numeric',
                month: 'numeric',
                year: 'numeric'
            };
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        today.setMilliseconds(0);
        // datepicker overall
        $.each($('.date_type'), function (i, n) {
            if (n.value.indexOf('NaN') > -1 || $(n).hasClass('hasDatepicker') || localStorage.getItem('periodStart') === null) {
                return true;
            }
            oldValsShow.push(n.value);
            if ($(n).val().indexOf('-') > -1) {
                dateSeparator = '-';
            }
            if ($(n).val().indexOf('_') > -1) {
                dateSeparator = '_';
            }
            ss = $(n).val().split(dateSeparator);
            workDate = new Date(ss[0], (ss[1] - 1), ss[2], 0, 0, 0, 0);
            if (dateSeparator === '.') {
                workDate = new Date(ss[2], (ss[1] - 1), ss[0], 0, 0, 0, 0);
            }
            if (window.localStorage.getItem('edit') === 'true' && $(n).attr('id').indexOf('guest') === -1) {
                if ($(n).attr('id').indexOf('started') > -1) {
                    ss = window.localStorage.getItem('startDate').split('_')
                } else {
                    ss = window.localStorage.getItem('endDate').split('_')
                }
                workDate = new Date(ss[0], ss[1], ss[2], 0, 0, 0, 0);
            }
            var index = $(n).attr('id').split('_'),
                ii = index[index.length - 1];
            if ($(n).attr('id').indexOf('guest') > -1 && window.localStorage.hasOwnProperty('guestStartDate_' + ii)) {
                if ($(n).attr('id').indexOf('started') > -1) {
                    ss = window.localStorage.getItem('guestStartDate_' + ii).split('_')
                } else {
                    ss = window.localStorage.getItem('guestEndDate_' + ii).split('_')
                }
                workDate = new Date(ss[0], ss[1], ss[2], 0, 0, 0, 0);
            } else if ($(n).attr('id').indexOf('guest') > -1) {
                workDate = new Date(ss[2], (ss[1] - 1), ss[0], 0, 0, 0, 0);
            }
            if (isNaN(workDate.getTime())) {
                if ($(n).attr('id').indexOf('started') > -1) {
                    ss = window.localStorage.getItem('startDate').split('_')
                } else {
                    ss = window.localStorage.getItem('endDate').split('_')
                }
                workDate = new Date(ss[0], ss[1], ss[2], 0, 0, 0, 0);
            }
            old_id = $(n).attr('id');
            new_id = (old_id.indexOf('show') === -1) ? 'show_' + old_id : old_id;
            if (!isGuest || old_id.indexOf('guest') === -1) {
                var tempS = window.localStorage.getItem('periodStart').split('-'),
                    tempE = window.localStorage.getItem('periodEnd').split('-');
                old_min = new Date(tempS[0], (parseInt(tempS[1], 10) - 1), tempS[2]);
                old_max = new Date(tempE[0], (parseInt(tempE[1], 10) - 1), tempE[2]);
            } else {
                var tempS = window.localStorage.getItem('startDate').split('_'),
                    tempE = window.localStorage.getItem('endDate').split('_');
                old_min = new Date(tempS[0], tempS[1], tempS[2]);
                old_max = new Date(tempE[0], tempE[1], tempE[2]);
            }
            if (today.getTime() > old_min.getTime()) {
                old_min = today;
            }
            old_val = $(n).val();
            //if (!$(n).hasClass('hasDatepicker') || docReady) {
                $(n).attr('data_separator', dateSeparator);
                $(n).attr('name', new_id);

                if ($(n).parent('div').children('.date-input').length === 0) {
                    if (old_id.indexOf('guest') > -1) {
                        old_name = old_id.split('_');
                        old_name = old_name[0] + '_' + old_name[1] + '_' + old_name[2] + '_' + old_name[3] + '[]';
                    } else {
                        old_name = old_id;
                    }
                   // $(n).parent('div').append('<input id="' + old_id + '" class="date-input" name="' + old_name + '" type="hidden" value="' + workDate.getFullYear() + '-' + window.smallerThenTen(window.parseInt(workDate.getMonth(), 10) + 1) + '-' + window.smallerThenTen(workDate.getDate()) + '" />');
                }
                if (old_id.indexOf('show_') === -1) {
                    $(n).attr('id', new_id);
                }

                $('#' + new_id).datepicker({
                    beforeShow: function () {
                        oldValsShow = $(this).attr('id');
                        oldValsShow = oldValsShow[oldValsShow.length - 1];
                    },
                    onClose: function () {
                        oldValsO = $(this).val().split(dateSeparator);
                        if (dateSeparator === '-') {
                            oldVal = oldValsO[0] + '-' + window.parseInt(oldValsO[1], 10) + '-' + window.parseInt(oldValsO[2], 10);
                        } else {
                            oldVal = window.parseInt(oldValsO[0], 10) + '.' + window.parseInt(oldValsO[1], 10) + '.' + oldValsO[2];
                        }
                        $('#' + oldValsShow).val(oldVal);
                        $('#' + oldValsShow).trigger('change');
                    },
                    defaultDate: workDate,
                    setDate: workDate,
                    dateFormat: lngFormat,
                    altFormat: 'yy-mm-dd',
                    altField: '[name="' + old_id + '"]',
                    minDate: old_min,
                    maxDate: old_max
                });

                $('#' + new_id).datepicker('refresh');
                $('#' + new_id).val(window.smallerThenTen(workDate.getDate()) + '.' + window.smallerThenTen(workDate.getMonth() + 1) + '.' + workDate.getFullYear());
            //}
            //$('#' + new_id).val(workDate.toLocaleDateString(window.locale, opts));
        });
    },
    checkNegativeDates: function (start, end) {
        "use strict";
        var x = (end.getTime() - start.getTime()) / 1000 / 60 / 60 / 24,
        counter = Math.round(x * 10) / 10;
        if (isNaN(parseInt(counter, 10)) || parseInt(counter, 10) < 1) {
            $('#night_nan').show();
            return false;
        }

    },
    toggleOtherHost: function (mid, text, is_visible) {
        "use strict";
        var gf = $('#guestFormID_' + mid),
            username = (text !== undefined) ? text : '';
        if (!is_visible) {
            $('#reservation_guest_num_' + mid).val(0).attr('readonly', true);

            $('#reservation_guest_started_at_' + mid).attr('readonly', true).addClass('other-host');
            $('#reservation_guest_ended_at_' + mid).attr('readonly', true).addClass('other-host');
            $('#reservation_guest_role_tax_night_' + mid).val(0).attr('readonly', true);
            $('#reservation_guest_guests_' + mid).val(12).attr('readonly', true);
            $('label[for="reservation_guest_guests_' + mid + '"]').text(window.langRes.guest_other_host);
            gf.children().find('.for_others')
                .append('<input disabled="disabled" name="other_hoster_id" id="other_hoster_id" type="text" class="form-control" value="' + username + '" />')
                .removeClass('col-xs-12 col-sm-3 col-md-2')
                .addClass('col-xs-12 col-sm-4 col-md-3');
            gf.children('div :eq(4)').hide();
        } else {
            $('label[for="reservation_guest_guests_' + mid + '"]').text(window.langRes.guest_kind);
            $('#other_hoster_id').remove();
            $('#reservation_guest_guests_' + mid).attr('readonly', false);
            $('#reservation_guest_started_at_' + mid).attr('readonly', false).removeClass('other-host');
            $('#reservation_guest_ended_at_' + mid).attr('readonly', false).removeClass('other-host');
            $('#reservation_guest_num_' + mid).val(1).attr('readonly', false);
            $('#userIdAb').val('').attr('disabled', true);
            gf.children('div :eq(3)')
                .addClass('col-xs-12 col-sm-3 col-md-2')
                .removeClass('col-xs-12 col-sm-4 col-md-3');

            gf.children('div :eq(4)').show();
        }
        $('#reservation_guest_num_' + mid).trigger('change');
        $('#reservation_guest_nights_show_' + mid).val(0).attr('readonly', true);
    },
    getUserList: function () {
        "use strict";
        $.ajax({
            type: 'GET',
            url: 'users/simplelist',
            async: false,
            success: function (d) {
                window.unAuthorized(d);
                window.userlist = d;
            }
        });
    },
    getUserListByClan: function (cid) {
        "use strict";
        $.ajax({
            type: 'GET',
            url: 'users/notthisclanlist',
            async: false,
            data: {
                clan_id: cid
            },
            success: function (d) {
                window.unAuthorized(d);
                window.userlist = d;
            }
        });
    },
    getGuestRoles: function (pid, el) {
        "use strict";
        var instance = this;
        $.ajax({
            type: 'GET',
            url: 'reservation/guestlist/',
            data: {
                period_id: pid
            },
            success: function (d) {
                window.unAuthorized(d);
                $.each($('[id^="' + el + '"]'), function (i, n) {
                    var valSet = n.value;
                    $(n)
                        .find('option')
                        .remove()
                        .end();
                    $.each(d, function (v, r) {
                        $(n).append(
                            $('<option></option>').val(v).html(r)
                        );
                    });
                    $(n).val(valSet);
                    return d;
                });
            }
        });
    },
    getCurrentPeriodClan: function (pid) {
        "use strict";
        var instance = this;
        $.ajax({
            type: 'GET',
            url: 'period/current',
            data: {
                period_id: pid
            },
            success: function (d) {
                window.unAuthorized(d);
                window.clan_id = d.id;
                instance.getGuestRoles(pid, 'reservation_guest_guests_');
                instance.getUserListByClan(d.clan_id);
            }
        });
    },
    calcReservationTotals: function () {
        "use strict";
        var c = 0.0;
        $.each($('[id^="reservation_guest_role_tax_night_show_total_"]'), function (i, n) {
            var amount = window.parseFloat($(n).val());
            c += amount;
        });
        if (isNaN(c)) {
            c = 0.0;
        }
        if (window.userRoles.indexOf('GU') > -1) {
            var overSeaTotal = window.parseInt($('#reservation_nights_show').val(), 10) * 40 + c;
            $('#reservation_total_sum').val(overSeaTotal.toFixed(2));
            $('#overSeaLabel').html('Übersee-Palazziner');
            window.localStorage.setItem('totalReservation', overSeaTotal.toFixed(2));
        } else {
            $('#reservation_total_sum').val(this.toFixed(c, 2));
            window.localStorage.setItem('totalReservation', this.toFixed(c, 2));
        }
    },
    calcGuestsTotals: function (mid, taxOld) {
        "use strict";
        var guestId = $('#reservation_guest_guests_' + mid).val(),
            tt = [],
            nval = $('#reservation_guest_nights_show_' + mid).val(),
            ngval = ($('#reservation_guest_num_' + mid).val() === undefined) ? 1 : $('#reservation_guest_num_' + mid).val(),
            nights = window.parseFloat(nval),
            guests = window.parseFloat(ngval),
            tax = (tt.length > 0) ? window.parseFloat(tt[0]) : 0,
            total = tax * nights * guests;
        if (taxOld === undefined) {
            $.each(window.roles, function (i, n) {
                if (n.id === window.parseInt(guestId, 10)) {
                    tt.push(window.roles[i].role_tax_night);
                    return false;
                }
            });
        } else {
            tt.push(taxOld);
        }
        if (isNaN(total)) {
            total = 0.0;
        }
        tax = (tt.length > 0) ? window.parseFloat(tt[0]) : 0;
        total = tax * nights * guests;
        if (window.userRoles.indexOf('GU') > -1) {
            //total += window.parseInt($('#reservation_nights_show').val(), 10) * 40;
            $('#overSeaLabel').html('Übersee-Palazziner');

            //$('#reservation_total_sum').val(overSeaTotal.toFixed(2));
        }
        $('#reservation_guest_role_tax_night_' + mid).val(tax.toFixed(2));
        $('#reservation_guest_role_tax_night_real_' + mid).val(tax.toFixed(2));
        $('#reservation_guest_role_tax_night_show_total_' + mid).val(total.toFixed(2));
        $('#reservation_guest_role_tax_night_total_' + mid).val(total.toFixed(2));
    },
    freeBedChecker: function (start, end, sid) {
        "use strict";
        var sd = new Date(start.getFullYear(), start.getMonth(), start.getDate(), 0, 0, 0, 0),
            ed = new Date(end.getFullYear(), end.getMonth(), end.getDate(), 0, 0, 0, 0),
            counter = 0,
            resId = $('#res_id').val(),
            freeBeds = 0,
            totalBeds = window.parseInt(window.settings.setting_num_bed, 10),
            ss,
            ff,
            newBeds = window.parseInt($('#reservation_guest_sum_num').val(), 10),
            oldBeds = 0,
            saveStorageEntries = [],
            beds = 0,
            departed = '',
            res,
            noRes = false;
        $.ajax({
            type: 'GET',
            url: 'reservation/edit',
            async: false,
            data: {
                res_id: resId,
            },
            success: function (d) {
                window.unAuthorized(d);
                res = $.parseJSON(d)[0];
            }
        });
        while (sd.getTime() < ed.getTime()) {
            ss = sd.getFullYear() + '-' + window.smallerThenTen(sd.getMonth()) + '-' + window.smallerThenTen(sd.getDate());
            ff = sd.getFullYear() + '_' + window.smallerThenTen(sd.getMonth()) + '_' + window.smallerThenTen(sd.getDate());
            departed = $('.departed_' + ff).text();
            oldBeds = window.parseInt(window.localStorage.getItem(ss), 10);
            if (isNaN(oldBeds)) {
                oldBeds = 0;
                noRes = true;
            }
            if (res !== undefined) {
                beds = (res[ff] + 1);
            }
            if (isNaN(beds)) {
                beds = 0;
                oldBeds = 0;
            }
            if (res !== undefined) {
                freeBeds = totalBeds - oldBeds + beds;
            } else {
                freeBeds = totalBeds - oldBeds;
            }
            newBeds = window.parseInt($('#reservation_guest_sum_num').val(), 10);
            // not enough free beds
            if (freeBeds < newBeds) {
                $('#freebeds_start').html(window.langRes.free_beds_at + ' ' + window.smallerThenTen(sd.getDate()) + '.' + window.smallerThenTen(sd.getMonth() + 1) + '.' + sd.getFullYear() + ': ' + freeBeds + '<br>' + window.langRes.new_beds_at + ': ' + newBeds);
                $('#guest-form-id').text(sid);

                this.guestCounter();
                $('#no_free_beds').show();
                counter -= 2;
                $('#saveEditReserv').attr('disabled', true);
                return false;
            } else {
                $('#saveEditReserv').attr('disabled', false);
                if (res === undefined && noRes) {
                    //window.localStorage.setItem(ss, (oldBeds + newBeds));
                } else if (noRes) {
                    console.log(oldBeds, newBeds, (oldBeds + (newBeds - oldBeds)))
                    saveStorageEntries.push(
                        {
                            local_storage_date: sd.getFullYear() + '-' + window.smallerThenTen((sd.getMonth() + 1)) + '-' + window.smallerThenTen(sd.getDate()) + ' 00:00:00',
                            local_storage_number: (oldBeds + (newBeds - oldBeds))
                        })
                    window.localStorage.setItem('saveLocalStorage', JSON.stringify(saveStorageEntries));
                }
            }
            sd.setDate(sd.getDate() + 1);
            counter += 1;
        }
        return true;
    },
    nightCounter: function (start, end, sid) {
        "use strict";
        var //sd = new Date(Date.UTC(start.getFullYear(), start.getMonth(), start.getDate(), 0, 0, 0, 0)),
            //ed = new Date(Date.UTC(end.getFullYear(), end.getMonth(), end.getDate(), 0, 0, 0, 0)),
            sd = new Date(start.getFullYear(), start.getMonth(), start.getDate(), 0, 0, 0, 0),
            ed = new Date(end.getFullYear(), end.getMonth(), end.getDate(), 0, 0, 0, 0),
            cc = (ed.getTime() - sd.getTime()) / 1000 / 60 / 60 / 24,
            counter = parseInt(cc, 10);
        if (sid !== undefined) {
            $('#reservation_guest_nights_show_' + sid).val(counter);
            $('#reservation_guest_nights_' + sid).val(counter);
        } else {
            $('#reservation_nights_show').val(counter);
        }
    },
    guestCounter: function () {
        "use strict";
        var c = 0;
        $.each($('[id^="reservation_guest_num_"]'), function (i, n) {
            c += window.parseInt($(n).val(), 10);
        });
        this.fillGuestNumberInfo(c);
        return c;
    },
    fillGuestNumberInfo: function (n) {
        "use strict";
        var nn = window.parseInt(n, 10);
        $('#reservation_guest_sum_num').val((nn + 1));
        if ((nn + 1) === 1) {
            $('#info_guest_num_text_one').text('');
            $('#info_guest_num_text_two').text('');
            return;
        }
        $('#info_guest_num_number').text(nn);
        if (nn > 1) {
            $('#info_guest_num_text_two').text(window.langRes.guest_many[2]);
            $('#info_guest_num_text_one').text(window.langRes.me_and);

        } else if (nn === 1) {
            $('#info_guest_num_text_two').text(window.langRes.guest_many[0]);
            $('#info_guest_num_text_one').text(window.langRes.me_and);
        } else {
            $('#info_guest_num_text_two').text(window.langRes.guest_many[0]);
        }
    },
    removeGuestForm: function () {
        "use strict";
        $('[id^="guestFormID_"]').remove();
    },
    removeGuestFormById: function (id) {
        "use strict";
        $('#guestFormID_' + id).remove();
    },
    deleteGuest: function (num, start, end, guest_id, form_id) {
        "use strict";
        var instance = this;
        $.ajax({
            type: 'POST',
            url: 'reservation/guests/delete',
            data: {
                reservation_id: $('#res_id').val(),
                guest_id: guest_id
            },
            success: function (d) {
                window.unAuthorized(d);
                var sd = new Date(start),
                    ed = new Date(end),
                    beds,
                    ds;
                if (d.deleted) {
                    while (sd.getTime() < ed.getTime()) {
                        ds = sd.getFullYear() + '-' + window.smallerThenTen(sd.getMonth()) + '-' + window.smallerThenTen(sd.getDate());
                        beds = window.localStorage.getItem(ds);
                        window.localStorage.setItem(ds, (window.parseInt(beds, 10) - window.parseInt(num, 10)));
                        sd.setDate(sd.getDate() + 1);
                    }
                    instance.removeGuestFormById(form_id);
                }
                if (d.user_id_ab) {
                    $('#userIdAb').val('');
                }
                instance.numberGuestForms();
                instance.disableSaving();
            }
        });
    },
    addGuestsForm: function (obj, other_clan_user) {
        "use strict";
        var instance = this,
            currentPeriodClan,
            guestFormStr = '#guestForm',
            resGuestStartedAt = '#reservation_guest_started_at',
            resGuestId = '#reserv_guest_id',
            resGuestEndedAt = '#reservation_guest_ended_at',
            resGuestsGuests = '#reservation_guest_guests',
            resGuestsNum = '#reservation_guest_num',
            resGuestsOld = '#rg_num_old',
            resGuestsRoleTaxNight = '#reservation_guest_role_tax_night',
            resGuestsRoleTaxNightReal = '#reservation_guest_role_tax_night_real',
            resGuestsNightShow = '#reservation_guest_nights_show',
            resGuestsRoleNightShowTotal = '#reservation_guest_role_tax_night_show_total',
            resGuestsRoleNightTotal = '#reservation_guest_role_tax_night_total',
            resGuestsNights = '#reservation_guest_nights',
            resGuestsSavenights = '#reservation_guest_save_nights',
            deleteGuest = '#deleteGuest',
            attrLab = 'label[for="',
            newGuest = [{
                guest_started_at: $('#show_reservation_started_at').val().replace(/-/g, '_'),
                guest_ended_at: $('#show_reservation_ended_at').val().replace(/-/g, '_'),
                guest_tax_role_id: 0,
                guest_number: 1,
                role_tax_night: 0.0,
                guest_night: $('#reservation_nights_show').val(),
                is_new_record: true,
                userIdAb: ''
            }],
            guest,
            counter = 0,
            isNew = false,
            dateStr,
            dateStrDot,
            startDate,
            today = new Date();
        if ($.isEmptyObject(obj)) {
            guest = newGuest;
            counter = $('#guestForm fieldset').length;
            isNew = true;
        } else {
            guest = obj.guests;
        }
        if (guest.length === 0) {
            instance.fillGuestNumberInfo(0);
        } else {
           // $(guestFormStr).append('<legend>Dauer des/der Gast/Gäste</legend>')
        }
        $.each(guest, function (i, n) {
            if (isNew) {
                i = counter;
            }
            $(guestFormStr).append(window.GuestForm);
            $('body, html').animate({ scrollTop: 0 }, 333);
            $(attrLab + resGuestStartedAt.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestStartedAt.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestId)
                .attr('id', $(resGuestId).attr('id') + '_' + i)
                .val(n.id);
            $(attrLab + resGuestEndedAt.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestEndedAt.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestEndedAt)
                .attr('id', $(resGuestEndedAt).attr('id') + '_' + i)
                .attr('min', n.guest_started_at.replace(/_/g, '-'))
                .attr('max', n.guest_ended_at.replace(/_/g, '-'))
                .val(n.guest_ended_at.replace(/_/g, '-'));
            $(attrLab + resGuestsGuests.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestsGuests.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestsRoleTaxNightReal)
                .attr('id', $(resGuestsRoleTaxNightReal).attr('id') + '_' + i);
            $(resGuestsRoleTaxNight)
                .attr('id', $(resGuestsRoleTaxNight).attr('id') + '_' + i);
            $(attrLab + resGuestsNum.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestsNum.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestsNum)
                .attr('id', $(resGuestsNum).attr('id') + '_' + i)
                .val(n.guest_number);
            if (n.is_new_record) {
                //instance.getGuestRoles(window.localStorage.getItem('periodId'), resGuestsGuests.substring(1));
                instance.getGuestRoles($('#period_id').val(), resGuestsGuests.substring(1));
                $(resGuestsGuests)
                    .attr('id', $(resGuestsGuests).attr('id') + '_' + i)
                    .val(0);
                $(resGuestsNum + '_' + i).attr('disabled', true);
            } else {
                $(resGuestsGuests)
                    .attr('id', $(resGuestsGuests).attr('id') + '_' + i)
                    .val(n.guest_tax_role_id);
                $(resGuestsRoleTaxNightReal + '_' + i)
                    .val(n.guest_tax);
                $(resGuestsRoleTaxNight + '_' + i)
                    .val(n.guest_tax);
            }
            $(attrLab + resGuestsOld.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestsOld.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestsOld)
                .attr('id', $(resGuestsOld).attr('id') + '_' + i)
                .val(n.guest_number);
            $(attrLab + resGuestsRoleTaxNight.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestsRoleTaxNight.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestsRoleNightShowTotal)
                .attr('id', $(resGuestsRoleNightShowTotal).attr('id') + '_' + i);
            $(attrLab + resGuestsNightShow.substring(1) + '"]')
            $(resGuestsRoleNightTotal)
                .attr('id', $(resGuestsRoleNightTotal).attr('id') + '_' + i);
            $(attrLab + resGuestsNightShow.substring(1) + '"]')
                .attr('for', $(attrLab + resGuestsNightShow.substring(1) + '"]').attr('for') + '_' + i);
            $(resGuestsNightShow)
                .attr('id', $(resGuestsNightShow).attr('id') + '_' + i)
                .val(n.guest_night);
            $(resGuestsNights)
                .attr('id', $(resGuestsNights).attr('id') + '_' + i)
                .val(n.guest_night);
            $(resGuestsSavenights)
                .attr('id', $(resGuestsSavenights).attr('id') + '_' + i);
            $(deleteGuest)
                .attr('id', $(deleteGuest).attr('id') + '_' + i);
            $('#guestFormID')
                .attr('id', $('#guestFormID').attr('id') + '_' + i);
            instance.calcGuestsTotals(i, n.guest_tax);
            instance.calcReservationTotals();
            instance.guestCounter();
            if (n.guest_started_at.indexOf('.') > -1) {
                dateStr = n.guest_started_at.split('.');
                startDate = new Date(dateStr[2], (dateStr[1] - 1), dateStr[0], 0, 0, 0, 0);
            } else {
                dateStr = n.guest_started_at.split('_');
                startDate = new Date(dateStr[2], (dateStr[1] - 1), dateStr[0], 0, 0, 0, 0);
            }
            //startDate = new Date(Date.UTC(dateStr[0], (dateStr[1] - 1), dateStr[2], 0, 0, 0, 0));
            if (!isNew) {
                window.disableInputsBeforeToday(startDate, [resGuestsGuests + '_' + i, resGuestsNum + '_' + i]);
            }
            if (window.isYesterDay(startDate) && isNew) {
                $(resGuestStartedAt)
                    .attr('id', $(resGuestStartedAt).attr('id') + '_' + i)
                    .attr('min', today.getFullYear() + '-' + window.smallerThenTen(today.getMonth() + 1) + '-' + window.smallerThenTen(today.getDate()))
                    .attr('max', n.guest_ended_at.replace(/_/g, '-'))
                    .attr('readonly', false)
                    .val(today.getFullYear() + '-' + window.smallerThenTen(today.getMonth() + 1) + '-' + window.smallerThenTen(today.getDate()));
                $('#deleteEditReserv').attr('disabled', true);
            } else {
                $(resGuestStartedAt)
                    .attr('id', $(resGuestStartedAt).attr('id') + '_' + i)
                    .attr('min', n.guest_started_at.replace(/_/g, '-'))
                    .attr('max', n.guest_ended_at.replace(/_/g, '-'))
                    .val(n.guest_started_at.replace(/_/g, '-'));
            }
            if ($('#userIdAb').val() !== '' && n.guest_tax_role_id === '12') {
                instance.toggleOtherHost(i, other_clan_user, false);
            }
            $('#guestForm').animate({ scrollTop: $('#guestFormID_' + i).position().top }, "slow");
            $('#saveEditReserv').attr('disabled', true);

        });
        if (!isNew) {
            $('[id^="reservation_guest_save_nights_"]').last().attr('disabled', false);
        } else {
            $('[id^="reservation_guest_save_nights_"]').attr('disabled', true);
            $('#addGuest').last().attr('disabled', true);
            //$('#deleteRes').last().attr('disabled', true);
        }
        if ($('#isEditedRes').val() === '1') {
            $.each($('[id^="reservation_guest_guests_"], [id^="reservation_guest_num_"]'), function (i, n) {
                $(n).attr('readonly', false);
            })
        }
        window.disableDateInputsBeforeToday('other-host');
        instance.adaptInputs(true, false);
        instance.numberGuestForms();
        instance.disableSaving();
    },
    editReservation: function (toDelete, resId) {
        "use strict";
        var instance = this,
            startArr = [],
            endArr = [],
            startStr = '',
            endStr = '';
        $.ajax({
            type: 'GET',
            url: 'reservation/edit',
            data: {
                reservation_started_at: $('#reservation_started_at').val(),
                reservation_ended_at: $('#reservation_ended_at').val(),
                period_id: $('#period_id').val(),
                res_id: resId,
                reservation_nights: $('#reservation_nights').val(),
                to_delete: toDelete
            },
            success: function (d) {
                window.unAuthorized(d);
                var currentPeriodClan,
                    data = $.parseJSON(d),
                    dataOne = data[0];
                startArr = dataOne.reservation_started_at.split('_');
                startStr = startArr[0] + '_' + window.smallerThenTen((window.parseInt(startArr[1], 10) - 1)) + '_' + startArr[2];
                endArr = dataOne.reservation_ended_at.split('_');
                endStr = endArr[0] + '_' + window.smallerThenTen((window.parseInt(endArr[1], 10) - 1)) + '_' + endArr[2];
                $.each(dataOne.guests, function (i, n) {
                    var s = n.guest_started_at.split('_'),
                        e = n.guest_ended_at.split('_');
                    window.localStorage.setItem('guestStartDate_' + i, s[0] + '_' + window.smallerThenTen((window.parseInt(s[1], 10) - 1)) + '_' + s[2]);
                    window.localStorage.setItem('guestEndDate_' + i, e[0] + '_' + window.smallerThenTen((window.parseInt(e[1], 10) - 1)) + '_' + e[2]);
                });
                window.localStorage.setItem('startDate', startStr);
                window.localStorage.setItem('endDate', endStr);
                window.localStorage.setItem('periodId', dataOne.period_id);
                window.localStorage.setItem('nights', dataOne.reservation_nights);
                window.localStorage.setItem('edit', true);
                instance.showNewEditReservation(dataOne);
                instance.adaptInputs(false, true);
                instance.addGuestsForm(data[0], dataOne.user_id_ab_name);
                $('#reservation_guest_sum_num_old').val($('#reservation_guest_sum_num').val());
                currentPeriodClan = instance.getCurrentPeriodClan(dataOne.period_id);
            }
        });
    },
    deleteReservation: function (id) {
        "use strict";
        var ss,
            instance = this,
            y = window.localStorage.getItem('startDate').split('_'),
            z = window.localStorage.getItem('endDate').split('_'),
            dateNow = new Date(Date.UTC(y[0], y[1], y[2], 0, 0, 0, 0)),
            dateStart = new Date(Date.UTC(y[0], y[1], y[2], 0, 0, 0, 0)),
            dateEnd = new Date(Date.UTC(z[0], z[1], z[2], 0, 0, 0, 0));
        dateNow = new Date(y[0], y[1], y[2], 0, 0, 0, 0);
        dateStart = new Date(y[0], y[1], y[2], 0, 0, 0, 0);
        dateEnd = new Date(z[0], z[1], z[2], 0, 0, 0, 0);
        dateEnd.setDate(dateEnd.getDate() + 1);
        window.setCurrentCalendarDate(dateStart);
        $.ajax({
            type: 'POST',
            url: 'reservation/delete',
            //crossDomain: true,
            data: {
                res_id: id,
                reservation_started_at: dateNow.getFullYear() + '-' + window.smallerThenTen(dateNow.getMonth() + 1) + '-' + window.smallerThenTen(dateNow.getDate())
            },
            error: function (xhr, status) {
                document.write(xhr.responseText)
            },
            success: function (data) {
                window.unAuthorized(data);
                if (data.hasOwnProperty('failed')) {
                    $('#no_delete_reservation').modal('hide');
                    $('#reset_res').trigger('click');
                    return false;
                }
                $('#users_res')
                    .find('option')
                    .remove()
                    .end();
                window.fillSelect($('#users_res'), data, false);
                while (dateNow.getTime() < dateEnd.getTime()) {
                    ss = dateNow.getFullYear() + '-' + window.smallerThenTen(dateNow.getMonth()) + '-' + window.smallerThenTen(dateNow.getDate());
                    window.localStorage.removeItem(ss);
                    dateNow.setDate(dateNow.getDate() + 1);
                    $('#todaysDate_' + ss.replace(/-/g, '_')).children().first().removeClass('day-reserved-date');
                    $('#data_' + ss.replace(/-/g, '_')).html('');
                }
                $('#reset_res').trigger('click');
                window.setCurrentCalendarDate(dateStart);
                window.location.reload();
            }
        });
    },
    showNewEditReservation: function (reservationData) {
        "use strict";
        var instance = this,
            inputsID = $('#res_id'),
            otherResIDs,
            startArr = window.localStorage.getItem('startDate').split('_'),
            startStr = startArr[2] + '.' + window.smallerThenTen((window.parseInt(startArr[1], 10) + 1)) + '.' + startArr[0],
            endArr = window.localStorage.getItem('endDate').split('_'),
            endStr = endArr[2] + '.' + window.smallerThenTen((window.parseInt(endArr[1], 10) + 1)) + '.' + endArr[0],
            dotStr = endArr[0] + '_' + window.smallerThenTen(window.parseInt(endArr[1], 10)) + '_' + endArr[2],
            dotStrPeriod = $('#dots_' + dotStr),
            periodId = (dotStrPeriod.attr('data_period_id') === undefined) ? window.localStorage.getItem('periodId') : dotStrPeriod.attr('data_period_id'),
            user_id_ab = (reservationData === null) ? '' : reservationData.user_id_ab,
            periodStart = (dotStrPeriod.attr('data_period_start') === undefined) ? window.localStorage.getItem('periodStart') : dotStrPeriod.attr('data_period_start'),
            periodClan = (dotStrPeriod.attr('data_period_clan_id') === undefined) ? window.localStorage.getItem('periodClan') : dotStrPeriod.attr('data_period_clan_id'),
            periodEnd = (dotStrPeriod.attr('data_period_end') === undefined) ? window.localStorage.getItem('periodEnd') : dotStrPeriod.attr('data_period_end'),
            // datepicker overall
            inputs = $('[id^="show_reservation_"]'), //(!window.Modernizr.inputtypes.date) ? $('[id^="show_reservation_"]') : $('[id^="reservation_"]'),
            resStartInput = $(inputs).first(),
            resEndInput = $(inputs).eq(1),
            pShow = '',
            existentResId = $(),
            ps = periodStart.split('-'),
            testDate = new Date(ps[0], (ps[1] - 1), ps[2], 0, 0, 0, 0),
            today = new Date(),
            el = $('#editReservMenu'),
            checkBedStartDate = new Date(startArr[0], startArr[1], startArr[2]),
            checkBedEndDate = new Date(endArr[0], endArr[1], endArr[2]);
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        today.setMilliseconds(0);
        window.toggleStuff(el);

        if (today.getTime() >= testDate.getTime()) {
            ps = today.getFullYear() + '-' + window.smallerThenTen((today.getMonth() + 1)) + '-' + window.smallerThenTen(today.getDate());
        } else {
            ps = periodStart;
        }

        $('#choosenDates')
            .removeClass('btn-default')
            .addClass('btn-success')
            .attr('disabled', false);
        $('#reset_res')
            .addClass('btn-default')
            .removeClass('btn-success');
        pShow = ($('#free-bed_' + dotStr).html() === undefined) ? $('#free_beds_more_' + dotStr).html().replace(/\<br\>/g, '/') : $('#free-bed_' + dotStr).html().replace(/\<br\>/g, '/');
        $('#p_show').html(pShow);
        $('#p_show').children()
            .removeClass('free-beds-more')
            .css({
                fontSize: '22px',
                textAlign: 'center',
                display: 'block !important'
            });
        if (reservationData === null) {
            //free_beds_more_
            for(var i = 0; i < 15; i++){
                if (window.localStorage.hasOwnProperty('guestStartDate_' + i)) {
                    window.localStorage.removeItem('guestStartDate_' + i)
                } else {
                    break;
                }
                if (window.localStorage.hasOwnProperty('guestEndDate_' + i)) {
                    window.localStorage.removeItem('guestEndDate_' + i)
                } else {
                    break;
                }
            }
            $('#saveEditReserv').attr('disabled', false);
            $('#info_guest_num_text_one').text(window.langRes.me_alone);
            $('#info_guest_num_number').text('');
            $('#info_guest_num_text_two').text(window.langRes.guest_many[2]);
            $('#deleteEditReserv').attr('disabled', true);
            $.each($('input[type=text], input[type=number]'), function (i, n) {
                n.value = '';
            });
            $('#reservation_guest_sum_num').val(1);
            $('#reservation_guest_sum_num_old').val(1);

        } else {
            $('#saveEditReserv').attr('disabled', true);
            $('#deleteEditReserv').attr('disabled', false);
            inputsID.val(reservationData.id);
        }
        if (instance.cCounter > 0 && this.id === 'goReservTo_') {
            return false;
        }
        if (this.id === 'deleteEditReserv') {
            instance.cCounter = 0;
        }
        instance.cCounter = 1;
        $('#guestForm').html('');
        $.each($('[id^="reservation_guest"]'), function (i, n) {
            if ($(n).attr('id').indexOf('reservation_guest_started_at_') > -1) {
                $(n)
                    .val(startStr)
                    .attr('min', startStr)
                    .attr('max', endStr);
            }
            if ($(n).attr('id').indexOf('reservation_guest_ended_at_') > -1) {
                $(n)
                    .val(endStr)
                    .attr('min', startStr)
                    .attr('max', endStr);
            }
            instance.adaptInputs(true, false);
        });
        resStartInput
            .val(startStr)
            //.attr('min', ps)
            //.attr('max', periodEnd);
        resEndInput
            .val(endStr)
            //.attr('min', ps)
            //.attr('max', periodEnd);

        $('#period_clan_id').val(periodClan);

        instance.nightCounter(new Date(startArr[0], startArr[1], startArr[2], 0, 0, 0, 0), new Date(endArr[0], endArr[1], endArr[2], 0, 0, 0, 0));
        instance.addClickedDays(null);
        $('#addGuest').attr('disabled', false);
        $('#goReservTo_').attr('disabled', true);
        $('#period_id').val(periodId);
        if (user_id_ab !== null) {
            if (user_id_ab.length > 0) {
                $('#userIdAb').val(user_id_ab);
            }
        }
        instance.calcReservationTotals();
    },

    /**
     *
     * @param d Reservation object
     */
    addReservationDays: function (d) {
        "use strict";
        var res,
            z,
            clan,
            clan_code,
            reservationRecord = d,
            s = reservationRecord.reservation_started_at.split('_'),
            e = reservationRecord.reservation_ended_at.split('_'),
            startDate = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
            endDate = new Date(e[0], (e[1] - 1), e[2], 0, 0, 0, 0),
            startId,
            otherGuestStr = '',
            startSelector,
            guestStr,
            manyGuestsLabel = window.bedLabel,
            r,
            t,
            editLink,
            editLinkPlus = '<span class="user_number_text"></span>',
            authUser = window.userId,
            dbGuestSum = 0,
            otherHostStr = '',
            showGuestSum,
            guessWhoWidth = [],
            showDepart,
            saveStorageEntries = [],
            freeBeds;
        Object.keys(d)
            .forEach(function (key) {
                var c,
                    s,
                    ss,
                    sss,
                    instance = d,
                    id = d.id;
                if (/^20/.test(key)) {
                    c = key.split('_');
                    s = c[0] + '-' + c[1] + '-' + c[2];
                    window.localStorage.setItem('reservationID_' + id + '_' + s, instance[c[0] + '_' + c[1] + '_' + c[2]]);
                    window.localStorage.setItem(s, (window.parseInt(instance[c[0] + '_' + c[1] + '_' + c[2]], 10) + 1));
                }
            });
        //this.saveLocalStorageEntries(saveStorageEntries);
        while (startDate.getTime() <= endDate.getTime()) {
            startId = startDate.getFullYear() + '_' + window.smallerThenTen(startDate.getMonth()) + '_' + window.smallerThenTen(startDate.getDate());
            // ToDo
            /* Line 745
             while (startDate.getTime() <= endDate.getTime()) {
             startId = startDate.getFullYear() + '_' + window.smallerThenTen(startDate.getMonth()) + '_' + window.smallerThenTen(startDate.getDate());
             if (reservationRecord[startId] !== undefined) {
             if (parseInt(reservationRecord[startId], 10) === 1) {
             t = manyGuestsLabel[0];
             } else {
             t = manyGuestsLabel[2];
             }
             this.guestSum = reservationRecord[startId] + 1;
                Line 753
             */
            // || reservationRecord.guests.length > 0 only for yesterdays saved res directly in db
            if (reservationRecord[startId] !== undefined && reservationRecord.guests.length > 0) {
                if (reservationRecord[startId] === undefined) {
                    $.each(reservationRecord.guests, function (i, n) {
                        dbGuestSum = parseInt(n.guest_number, 10);
                        reservationRecord[startId] = dbGuestSum;
                    });

                    //reservationRecord[startId] = 0;
                }
                t = manyGuestsLabel[1];
                if (startDate.getTime() !== endDate.getTime()) {
                    this.guestSum = reservationRecord[startId] + 1;
                } else {
                    this.guestSum = 0;
                }
                showGuestSum = this.guestSum;
                if ($('#data_' + startId).children('p').children('span').children('i').text().indexOf(window.langRes.depart) > -1) {
                    console.log('new', showGuestSum, startId);
                }
                guestStr = '<br><span>' + t + '</span> <span class="reservation_date_' + startId + '"><span class="guestData%' + startId + '">' + showGuestSum + '<span></span>';
                if (startDate.getTime() === endDate.getTime()) {
                    guestStr = '<br><span><i id="departedResID_' + reservationRecord.id + '_' + showGuestSum + '" class="departed_'+ startId + '">' + window.langRes.depart + '</i></span>';
                }
            } else {
                if (this.guestSum === 0) {
                    this.guestSum = 1;
                }

                showGuestSum = this.guestSum;
                guestStr = '<br><span>' + manyGuestsLabel[1] + '</span> <span class="reservation_date_' + startId + '"><span class="guestData%' + startId + '">' + showGuestSum + '<span></span>';
                if (startDate.getTime() == endDate.getTime()) {
                    guestStr = '<br><span><i id="departedResID_' + reservationRecord.id + '_' + showGuestSum + '" class="departed_'+ startId + '>' + window.langRes.depart + '</i></span>';
                }
                this.guestSum = 1;
            }
            if (reservationRecord.user_id_ab_name !== '' && reservationRecord.user_id !== reservationRecord.user_id_ab) {
                otherHostStr = ' class="otherHost_' + reservationRecord.user_id_ab + ' existentResId_' + reservationRecord.id + '"';
                otherGuestStr = '<p class="reserv"></p><span><a href="/user/profile/' + reservationRecord.user_id_ab + '">+ ' + window.langRes.guest_other_host + '</a></span></p>';
            } else {
                otherHostStr = '';
            }
            $(this.todayId + startId).append('<div' + otherHostStr + ' id="data_' + startId + '">');
            otherHostStr = '';
            startSelector = $('#data_' + startId);
            if (reservationRecord.user_id === authUser) {
                editLink = '<span class="guess_me"><a class="edit" id="editThisReserv_' + reservationRecord.id + '_' + startId + '">' + window.langDialog.edit + '</a></span>' +
                    editLinkPlus;
            } else {
                editLink = '<span class="guess_me"><a title="' + reservationRecord.user_login_name + '" href="/user/profile/' + reservationRecord.user_id + '">' + reservationRecord.user_login_name + '</a>' + editLinkPlus + '</span>';
            }
            startSelector.append('<p class="reserv guessWho_' + reservationRecord.user_id + ' reserv_guessWhat_' + reservationRecord.id + '">' + editLink + guestStr + '</p>' + otherGuestStr);
            startSelector.prev('.day-date').addClass('day-reserved-date');

            $(this.todayId + startId).append('</div>');
            r = (window.localStorage.getItem('free-bed_' + startId) === null) ? 0 : window.parseInt(window.localStorage.getItem('free-bed_' + startId), 10);
            window.localStorage.setItem('free-bed_' + startId, this.guestSum + r);
            freeBeds = window.parseInt(window.settings.setting_num_bed, 10) - window.localStorage.getItem('free-bed_' + startId);
            if (freeBeds < 0) {
                freeBeds = 0;
            }
            if (freeBeds === 0 && $('#data_' + startId).children('p').children('span').children('i').text().indexOf(window.langRes.depart) === -1) {
                $('#todaysDate_' + startId).addClass('not-allowed');

                if ($('#todaysDate_' + startId).find('[id^="editThisReserv_"]').length > 0) {
                    $('#todaysDate_' + startId).css({
                        'pointer-events': 'all',
                        opacity: '1',
                        cursor: 'auto'
                    })
                }

            }

            res = '<span id="AbsFreeBeds">' + freeBeds + '</span>/' + window.settings.setting_num_bed;
            clan = $('#dots_' + startId).children('img').attr('alt');
            clan_code = $('#dots_' + startId).children('img').attr('img_data_clan');
            if (clan === undefined) {
                clan = (window.localStorage.getItem('periodClan') === '1') ? 'Wolf' : 'Guggenbühl';
                clan_code = window.localStorage.getItem('periodClan');
            }
            if (startDate.getTime() === endDate.getTime()) {
                var superflu = $('#editThisReserv_' + reservationRecord.id + '_' + endDate.getFullYear() + '_' + window.smallerThenTen(endDate.getMonth()) +'_' + window.smallerThenTen(endDate.getDate()));
                superflu.attr('id', '').replaceWith('<span></span>');
            }
            z = '<div class="free-beds-more">' + window.showDate(startDate, 'long') + '<br>' + window.langRes.beds_free + ': ' + res + '<br>' + window.langRes.prior + ': <span class="' + clan_code + '-text">' + clan + '</span></div>';
            $('#free-bed_' + startId).html(z);

            startDate.setDate(startDate.getDate() + 1);
            this.guestSum = 1;
            if ($('.guessWho_' + reservationRecord.user_id)[0] !== undefined) {
                if (window.parseInt($('.guessWho_' + reservationRecord.user_id)[0].scrollWidth, 10) > window.parseInt($('#data_' + startId).width(), 10)) {
                    window.localStorage.setItem('guessWhoWidth', '.guessWho_' + reservationRecord.user_id);
                }
            }
        }
        window.setCalendarDayProfileText();
    },
    removeDepartedBeds: function (d) {
        var reservationRecord = d,
            s = reservationRecord.reservation_started_at.split('_'),
            e = reservationRecord.reservation_ended_at.split('_'),
            startDate = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
            endDate = new Date(e[0], (e[1] - 1), e[2], 0, 0, 0, 0),
            startId,
            startIdMinus;
        while (startDate.getTime() <= endDate.getTime()) {
            startId = startDate.getFullYear() + '_' + window.smallerThenTen(startDate.getMonth()) + '_' + window.smallerThenTen(startDate.getDate());
            startIdMinus = startId.replace(/_/g, '-');
            if ($('#data_' + startId).children('p').children('span').children('i').text().indexOf(window.langRes.depart) > -1) {
                var set = window.parseInt(window.localStorage.getItem(startIdMinus), 10);
                var newSet = set - (reservationRecord[startId] + 1);
                if (isNaN(newSet)) {
                    newSet = 0;
                }
                window.localStorage.setItem(startIdMinus, newSet);
                window.localStorage.setItem('free-bed_' + startId, newSet);
            }
            startDate.setDate(startDate.getDate() + 1);
        }
    },
    saveLocalStorageEntries: function (key) {
        "use strict";
        $('#loading').hide();
        $('.loading-day').hide();
        $.ajax({
            type: 'POST',
            url: 'reservation/savelocal',
            data: {key},
            error: function () {
                $.event.global = false;
            },
            success: function (data) {
                window.unAuthorized(data);
                console.log(data);
            }
        });
    },

    /**
     *
     * @param d Date object
     */
    addReservations: function (d) {
        "use strict";
        var instance = this,
            thisMonth = new Date(d.getFullYear(), d.getMonth(), 1, 0, 0, 0, 0),
            nextMonth = new Date(d.getFullYear(), d.getMonth(), 1, 0, 0, 0, 0),
            data_this_month,
            data_next_month;
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        thisMonth.setMonth(thisMonth.getMonth() - 1);
        data_this_month = thisMonth.getFullYear() + '-' + window.smallerThenTen(thisMonth.getMonth() + 1) + '-01';
        data_next_month = nextMonth.getFullYear() + '-' + window.smallerThenTen(nextMonth.getMonth() + 1) + '-01';
        $.ajax({
            type: 'GET',
            url: 'reservation/month',
            data: {
                this_month: data_this_month,
                next_month: data_next_month
            },
            success: function (data) {
                window.unAuthorized(data);
                var reservations = $.parseJSON(data);
                if (reservations.length > 0) {
                    jQuery.each(reservations, function (i, n) {
                        instance.addReservationDays(n);
                    });
                    Object.keys(window.localStorage)
                        .forEach(function (key) {
                            var c;
                            if (/^free-bed_/.test(key)) {
                                c = key.split('_');
                                window.localStorage.setItem(c[1] + '-' + c[2] + '-' + c[3], window.localStorage.getItem(key));
                                window.localStorage.removeItem(key);
                            }
                        });
                }
            }
        });
    },

    countClicks: function (s) {
        "use strict";
        var hasStart = (window.localStorage.getItem('startDate') !== null && window.localStorage.getItem('startDate').indexOf('NaN') === -1),
            ss = s.split('_'),
            s_exists_ss,
            e_exists_ss,
            firstDate,
            checkFirstDate,
            secondDate,
            startId,
            sss,
            ttt = [],
            storedSecondDate;
        if (hasStart) {
            s_exists_ss = window.localStorage.getItem('startDate').split('_');
            e_exists_ss = window.localStorage.getItem('endDate').split('_');
            //firstDate = new Date(Date.UTC(parseInt(s_exists_ss[0], 10), parseInt(s_exists_ss[1], 10), parseInt(s_exists_ss[2], 10), 0, 0, 0, 0));
            //secondDate = new Date(Date.UTC(parseInt(ss[1], 10), parseInt(ss[2], 10), parseInt(ss[3], 10), 0, 0, 0, 0));
            //storedSecondDate = new Date(Date.UTC(parseInt(e_exists_ss[0], 10), parseInt(e_exists_ss[1], 10), parseInt(e_exists_ss[2], 10), 0, 0, 0, 0));
            firstDate = new Date(parseInt(s_exists_ss[0], 10), parseInt(s_exists_ss[1], 10), parseInt(s_exists_ss[2], 10), 0, 0, 0, 0);
            checkFirstDate = new Date(parseInt(s_exists_ss[0], 10), parseInt(s_exists_ss[1], 10), parseInt(s_exists_ss[2], 10), 0, 0, 0, 0);
            secondDate = new Date(parseInt(ss[1], 10), parseInt(ss[2], 10), parseInt(ss[3], 10), 0, 0, 0, 0);
            storedSecondDate = new Date(parseInt(e_exists_ss[0], 10), parseInt(e_exists_ss[1], 10), parseInt(e_exists_ss[2], 10), 0, 0, 0, 0);
            while (checkFirstDate.getTime() <= secondDate.getTime()) {
                sss = checkFirstDate.getFullYear() + '_' + window.smallerThenTen(checkFirstDate.getMonth()) + '_' + window.smallerThenTen(checkFirstDate.getDate());
                if ($('#todaysDate_' + sss).hasClass('not-allowed')) {
                    ttt.push(window.smallerThenTen(checkFirstDate.getDate()) + '.' + window.smallerThenTen(checkFirstDate.getMonth() + 1) + '.' + checkFirstDate.getFullYear());
                }
                checkFirstDate.setDate(checkFirstDate.getDate() + 1);

            }
        } else {
            //firstDate = new Date(Date.UTC(parseInt(s_exists_ss[0], 10), parseInt(s_exists_ss[1], 10), parseInt(s_exists_ss[2], 10), 0, 0, 0, 0));
            firstDate = new Date(parseInt(ss[1], 10), parseInt(ss[2], 10), parseInt(ss[3], 10), 0, 0, 0, 0);
            s_exists_ss = null;
        }
        if ((storedSecondDate - secondDate) > (secondDate - firstDate)) {
            firstDate = secondDate;
            secondDate = storedSecondDate;
        }
        this.fireClicks(firstDate, secondDate);
    },

    addClickedDays: function (now) {
        "use strict";
        var startDate = window.localStorage.getItem('startDate'),
            endDate = window.localStorage.getItem('endDate'),
            s = startDate.split('_'),
            e = (now !== null) ? now.split('_') : endDate.split('_'),
            start = new Date(parseInt(s[0], 10), parseInt(s[1], 10), parseInt(s[2], 10), 0, 0, 0, 0),
            end = new Date(parseInt(e[0], 10), parseInt(e[1], 10), parseInt(e[2], 10), 0, 0, 0, 0),
            startId = start.getFullYear() + '_' + window.smallerThenTen(start.getMonth()) + '_' + window.smallerThenTen(start.getDate()),
            reserveBtn = '.reserv-btn',
            showChosenDates,
            opts = {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            },
            sec,
            fir;
        if (startDate === undefined) {
            return false;
        }
        fir = window.showDate(start, 'nozero');
        sec = window.showDate(end, 'nozero');
        showChosenDates = fir + ' ' + window.langStrings.dialog.until + ' ' + sec;

        $(window.calDayId).removeClass(this.firstAddedReservationCss);
        $(window.calDayId).removeClass(this.lastAddedReservationCss);
        $(window.calDayId).removeClass(this.addedReservationCss);
        $(this.todayId + startDate).addClass(this.firstAddedReservationCss);
        $(reserveBtn).remove();
        $('#choosenDates')
            .attr('disabled', false)
            .attr('name', s[1] + '_' + s[0])
            .html(
                window.langRes.goto_chosen_dates +
                    ':<br>' +
                    showChosenDates
            )
            .removeClass('btn-default')
            .addClass('btn-success');
        $('#reset_res')
            .removeClass('btn-default')
            .addClass('btn-success');
        $('#reservation_started_at').val(start.getFullYear() + '-' + window.smallerThenTen(start.getMonth() + 1) + '-' + window.smallerThenTen(start.getDate()));
        $('#reservation_ended_at').val(end.getFullYear() + '-' + window.smallerThenTen(end.getMonth() + 1) + '-' + window.smallerThenTen(end.getDate()));
        while (start.getTime() <= end.getTime()) {
            startId = start.getFullYear() + '_' + window.smallerThenTen(start.getMonth()) + '_' + window.smallerThenTen(start.getDate());
            $(this.todayId + startId).addClass(this.addedReservationCss);
            start.setDate(start.getDate() + 1);
        }
        $(this.todayId + startId).addClass(this.lastAddedReservationCss);

        if ($(reserveBtn).length === 0) {
            $(this.todayId + startId).append(
                '<div class="reserv-btn" id="btn-go-reserv_' + startId + '">' +
                '<a href="#editReserv" id="goReservTo_" class="btn btn-success btn Reserv guessWhat_">Reservieren</a>' +
                '<button id="reset_res_inline" class="btn btn-default">' +
                '<i class="far fa-trash-alt"></i>' +
                '</button>' +
                    '</div>'
            );
        }
    },

    fireClicks: function (first, second, noStyle) {
        "use strict";
        var one = first.getTime(),
            clickCounter = (window.localStorage.hasOwnProperty('cC')) ? window.parseInt(window.localStorage.cC, 10) : 0,
            two = 0,
            periodSelector = first.getFullYear() + '_' + window.smallerThenTen(first.getMonth()) + '_' + window.smallerThenTen(first.getDate()),
            dotStrPeriod = $('#dots_' + periodSelector),
            periodId = dotStrPeriod.attr('data_period_id'),
            periodStart = dotStrPeriod.attr('data_period_start'),
            periodClan = dotStrPeriod.attr('data_period_clan_id'),
            periodEnd = dotStrPeriod.attr('data_period_end'),
            combine = function (d) {
                return d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate());
            };
        if (clickCounter < 2) {
            clickCounter += 1;
        }
        if (second === undefined) {
            second = new Date(
                first.getFullYear(),
                first.getMonth(),
                first.getDate() + 1,
                0,
                0,
                0,
                0
            );
            two = second.getTime();
        } else {
            two = second.getTime();
        }
        if (two < one) {
            window.localStorage.setItem('startDate', combine(second));
            window.localStorage.setItem('endDate', combine(first));
        } else if (two === one) {
            second.setDate(second.getDate() + 1);
            window.localStorage.setItem('startDate', combine(first));
            window.localStorage.setItem('endDate', combine(second));
        } else {
            window.localStorage.setItem('startDate', combine(first));
            window.localStorage.setItem('endDate', combine(second));
        }
        window.localStorage.setItem('periodId', periodId);
        window.localStorage.setItem('periodStart', periodStart);
        window.localStorage.setItem('periodEnd', periodEnd);
        window.localStorage.setItem('periodClan', periodClan);
        window.localStorage.setItem('cC', clickCounter);
        if (noStyle === undefined || noStyle !== 'noStyle') {
            this.setStyle();
        }
    },

    setStyle: function () {
        "use strict";
        if (window.localStorage.getItem('endDate') === null || window.localStorage.getItem('startDate') === null) {
            return false;
        }
        var startDate = window.localStorage.getItem('startDate'),
            endDate = window.localStorage.getItem('endDate'),
            that = this,
            i = '',
            ss = (startDate !== null) ? startDate.split('_') : null,
            sss = endDate.split('_'),
            occupied = window.parseInt(window.localStorage.getItem(sss.join('-')), 10),
            totalBeds = window.parseInt(window.settings.setting_num_bed, 10),
            se,
            //start = (ss !== null) ? new Date(Date.UTC(ss[0], ss[1], ss[2], 0, 0, 0, 0)) : null,
            start = (ss !== null) ? new Date(ss[0], ss[1], ss[2], 0, 0, 0, 0) : null,
            end,
            dayEl = jQuery(window.calDayId);
        if (start === null || ss === null) {
            return;
        }
        dayEl.removeClass(this.addedReservationCss);
        dayEl.removeClass(this.firstAddedReservationCss);
        dayEl.removeClass(this.lastAddedReservationCss);
        if (window.localStorage.getItem('endDate') !== null && occupied !== totalBeds) {
            se = endDate.split('_');
            //end = new Date(Date.UTC(se[0], se[1], se[2], 0, 0, 0, 0));
            end = new Date(se[0], se[1], se[2], 0, 0, 0, 0);
            jQuery(this.todayId + se.join('_')).addClass(this.lastAddedReservationCss);
        }
        if (occupied !== totalBeds) {
            jQuery(this.todayId + ss.join('_')).addClass(this.firstAddedReservationCss);
        }
        while (start < end) {
            i = this.todayId + start.getFullYear() + '_' + window.smallerThenTen(start.getMonth()) + '_' + window.smallerThenTen(start.getDate());
            if (!jQuery(i).hasClass(that.firstAddedReservationCss) || !jQuery(i).hasClass(that.lastAddedReservationCss) && occupied !== totalBeds) {
                jQuery(i).addClass(that.addedReservationCss);
            }
            start.setDate(start.getDate() + 1);
        }
        this.addClickedDays(null);

    }
};
