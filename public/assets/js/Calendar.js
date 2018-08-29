/*jslint todo: true */
// ToDo {LaterOn} option for starting week at monday or sunday with language
(function ($) {
    "use strict";
    $.fn.Calendar = function (options) {

        var instance = $(this),
            defaults = {
                clickedDate: window.startDate,
                calendarWeek: 0,
                authID: function () {
                    $.ajax({
                        type: 'GET',
                        url: 'reservation/getuser',
                        async: false,
                        success: function (data) {
                            return data;
                        }
                    });
                }
            },
            settings = $.extend({
                todayInts: [
                    defaults.clickedDate.getFullYear(),
                    defaults.clickedDate.getMonth(),
                    defaults.clickedDate.getDate()
                ],
                startDate: defaults.clickedDate,
                duration: 5,
                globalSettings: null,
                calendarData: {
                    periods: window.userPeriods
                },
                locale: window.locale.split('-')[0]
            }, defaults, options),
            /**
             *
             * @param d Date object
             * @returns {number}
             */
            getLastMonthDay = function (d) {
                var dd = new Date(d),
                    c;
                dd.setMonth(dd.getMonth() + 1);
                dd.setDate(0);
                c = dd.getDate();
                return c;
            },
            injectPeriodClassCalendar = function (d) {
                var thisdate = d.getFullYear() + '-' + window.smallerThenTen(d.getMonth() + 1) + '-01';
                $.ajax({
                    type: 'GET',
                    url: 'reservation/period',
                    data: {
                        this_date: thisdate
                    },
                    success: function (data) {
                        var objs = $.parseJSON(data),
                            check,
                            ss,
                            s,
                            ee,
                            e,
                            start,
                            between,
                            thisPeriod,
                            end;
                        $.each(objs, function (i, n) {
                            ss = n.period_start.split(' ');
                            s = ss[0].split('-');
                            ee = n.period_end.split(' ');
                            e = ee[0].split('-');
                            //start = new Date(Date.UTC(s[0], (s[1] - 1), s[2], 0, 0, 0, 0));
                            //end = new Date(Date.UTC(e[0], (e[1] - 1), e[2], 0, 0, 0, 0));
                            start = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0);
                            end = new Date(e[0], (e[1] - 1), e[2], 0, 0, 0, 0);
                            between = thisdate.split('-');
                            thisPeriod = new Date(between[0], (between[1] - 1), between[2], 0, 0, 0, 0)
                            while (start.getTime() <= end.getTime()) {
                                check = start.getFullYear() + '_' + window.smallerThenTen(start.getMonth()) + '_' + window.smallerThenTen(start.getDate());
                                $('#dots_' + check)
                                    .attr('data_period_id', n.id)
                                    .attr('data_period_clan_id', n.clan_id)
                                    .attr('data_period_start', n.period_start.split(' ')[0])
                                    .attr('data_period_end', n.period_end.split(' ')[0])
                                    .html('<img img_data_clan="' + n.clan_code + '" class="' + n.clan_code + '-img" src="' + window.baseUrl + 'assets/img/' + n.clan_code + '.png" alt="' + n.clan_description + '" />');
                                $('#free_beds_more_' + check).append('<br>' + window.langRes.prior + ': <span class="' + n.clan_code + '-text">' + n.clan_description + '</span>');
                                start.setDate(start.getDate() + 1);
                            }
                        });
                        window.Reservation.fireClicks(defaults.clickedDate, defaults.clickedDate, 'noStyle');
                    }
                });
            },
            addSmallNavigation = function () {
                return '<div class="to-top small-show">' +
                    '<button class="btn btn-sm btn-default btn-left"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>' +
                    '<a href="#top" class="btn btn-sm btn-default btn-top"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></a>' +
                    '<button class="btn btn-sm btn-default btn-right"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>' +
                    '<button class="btn btn-sm btn-default btn-bottom"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></button>' +
                    '</div>' +
                    '</div>';
            },
            /**
             *
             * @param d Date object
             * @param isPrevNext (bool) previous or next month
             * @param withMonth (bool) show month
             * @returns {string}
             */
            addDayElement = function (d, el, isPrevNext) {
                var today = new Date(),
                    emptyC = '',
                    data = d.getDate() + '. ' + (d.getMonth() + 1);
                if (isPrevNext) {
                    emptyC = ' raw-empty-day';
                }
                today.setHours(0);
                today.setMinutes(0);
                today.setSeconds(0);
                today.setMilliseconds(0);
                el.append('<div id="todaysDate_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate()) + '"' +
                    'data_year="' + d.getFullYear() + '"' +
                    'data_month="' + window.smallerThenTen(d.getMonth()) + '"' +
                    'data_date="' + window.smallerThenTen(d.getDate()) + '"' +
                    'data_time="' + d.getTime() + '"' +
                    'class="calday raw-day' + emptyC + '">' +
                        '<div class="day-date">' +
                            '<span id="dots_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate()) + '"></span>' +
                            data +
                            '</span>' +
                            '<div class="free-bed" id="free-bed_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate()) + '">' +
                            '<div id="free_beds_more_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate()) + '" class="free-beds-more">' + window.showDate(d, 'long') + '<br>' + window.langRes.beds_free + ': ' + window.settings.setting_num_bed + '</div>' +
                            '</div>' +
                            '<div class="loading-day">' +
                                '<img src="' + window.baseUrl + 'assets/img/ajax-loader-bar.gif">' +
                            '</div>' +
                        '</div>' +
                    '</div>');
                if (today.getTime() > d.getTime()) {
                    document
                        .getElementById('todaysDate_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate()))
                        .style.pointerEvents = 'none';
                    $('#todaysDate_' + d.getFullYear() + '_' + window.smallerThenTen(d.getMonth()) + '_' + window.smallerThenTen(d.getDate())).css({opacity: '0.66', cursor: 'not-allowed'});
                }
            },
            checkEvents = function () {
                if (window.localStorage.cC === '1') {
                    window.localStorage.setItem('cC', 0);
                }
            },

            /**
             *
             * @param d Date object
             * @returns {Array}
             */
            checkGlobalCal = function (d) {
                var returns = [],
                    compStartVars = settings.globalSettings.setting_calendar_start.split('-'),
                    //compStart = new Date(Date.UTC(
                    //    parseInt(compStartVars[0], 10),
                    //    parseInt(compStartVars[1], 10) - 1,
                    //    parseInt(compStartVars[2], 10),
                    //    0,
                    //    0,
                    //    0,
                    //    0
                    //)
                    //    ),
                    //compEnd = new Date(Date.UTC(
                    //    parseInt(compStartVars[0], 10),
                    //    parseInt(compStartVars[1], 10) - 1,
                    //    parseInt(compStartVars[2], 10),
                    //    0,
                    //    0,
                    //    0,
                    //    0
                    //)
                    //    );

                    compStart = new Date(
                        parseInt(compStartVars[0], 10),
                        parseInt(compStartVars[1], 10) - 1,
                        parseInt(compStartVars[2], 10),
                        0,
                        0,
                        0,
                        0
                        ),
                    compEnd = new Date(
                        parseInt(compStartVars[0], 10),
                        parseInt(compStartVars[1], 10) - 1,
                        parseInt(compStartVars[2], 10),
                        0,
                        0,
                        0,
                        0
                        );

                compEnd.setFullYear(compEnd.getFullYear() + parseInt(settings.globalSettings.setting_calendar_duration, 10));
                compEnd.setMonth(11);
                compEnd.setDate(31);
                returns.year_prev = (d.getFullYear() > compStart.getFullYear()) ? '' : 'disabled';
                returns.year_next = (d.getFullYear() < compEnd.getFullYear()) ? '' : 'disabled';
                returns.month_prev = ((d.getMonth() > compStart.getMonth()) || (d.getFullYear() > compStart.getFullYear())) ? '' : 'disabled';
                returns.month_next = ((d.getMonth() < compEnd.getMonth()) || (d.getFullYear() < compEnd.getFullYear())) ? '' : 'disabled';
                return returns;
            },
            /**
             *
             * @param el jQuery object
             */
            createWeekGrid = function (el, d) {
                var i,
                    htmlStr = '',
                    showWeek,
                    fw = '',
                    gr = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), 0, 0, 0, 0));
                gr = new Date(d.getFullYear(), d.getMonth(), d.getDate(), 0, 0, 0, 0);
                gr.setDate(gr.getDate() - 1);
                if (d.getWeek() === 1) {
                    fw = '/1';
                }
                showWeek = gr.getWeek() + fw;
                htmlStr += '<div class="raw-day kw small-no-show">' + window.langStrings.calweek_short + '</div>';
                for (i = 0; i < settings.weekdayShortNames.length; i += 1) {
                    htmlStr += '<div id="' + settings.weekdayShortNames[i] + '_' + i + '" class="raw-day small-no-show">' + settings.weekdayShortNames[i] + '</div>';
                }
                htmlStr += '<div class="break small-no-show"></div>';
                htmlStr += '<div class="calday raw-day kw"><div class="small-show">' + window.langStrings.calweek + ': </div>' +
                    showWeek +
                    addSmallNavigation();
                el.prepend(htmlStr);
            },

            /**
             *
             * @param el jQuery object
             */
            createPreviousMonth = function (el, k) {
                var ak = new Date(k.getTime()),
                    end;
                ak.setMonth(ak.getMonth() - 1);
                end = getLastMonthDay(ak) + 1;
                ak.setDate(end - k.getDay());
                if (ak.getDay() === 0 && ak.getDate() === end) {
                    return;
                }
                if (ak.getDay() === 0 && ak.getDate() === 1) {
                    ak.setDate(ak.getDate() - 7);
                    end = getLastMonthDay(ak);
                }
                ak.setDate(ak.getDate() + 1);
                if (k.getTime() === ak.getTime()) {
                    return false;
                }
                do {
                    addDayElement(ak, el, true);
                    ak.setDate(ak.getDate() + 1);
                } while (ak.getDate() !== 1);
            },

            /**
             *
             * @param el jQuery object
             */
            createNextMonth = function (el) {
                var k = new Date(Date.UTC(
                        defaults.clickedDate.getFullYear(),
                        defaults.clickedDate.getMonth(),
                        defaults.clickedDate.getDate(),
                        0,
                        0,
                        0,
                        0
                    )
                        ),
                    end = getLastMonthDay(defaults.clickedDate);
                k = new Date(
                    defaults.clickedDate.getFullYear(),
                    defaults.clickedDate.getMonth(),
                    defaults.clickedDate.getDate(),
                    0,
                    0,
                    0,
                    0
                );
                k.setDate(end);
                if (k.getDay() > 0) {
                    do {
                        k.setDate(k.getDate() + 1);
                        addDayElement(k, el, true);
                    } while (k.getDay() !== 0);
                }
            },

            /**
             *
             * @param el jQuery object
             */
            createMonthGrid = function (el) {
                var monthObj,
                    weekToBig,
                    strWeek = '',
                    weekMeta = 0,
                    k = new Date(
                        defaults.clickedDate.getFullYear(),
                        defaults.clickedDate.getMonth(),
                        1,
                        0,
                        0,
                        0
                    ),
                    end = new Date(defaults.clickedDate.getFullYear(), defaults.clickedDate.getMonth(), getLastMonthDay(defaults.clickedDate), 0, 0, 0, 0);
                el.append('<div class="draw-month" id="draw-month_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="break">');
                monthObj = $('#draw-month_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear());
                createWeekGrid(monthObj, k);
                createPreviousMonth(monthObj, k);

                while ((k.getTime() + 1) <= end.getTime()) {
                    addDayElement(k, monthObj, false);
                    k.setDate(k.getDate() + 1);
                    if (k.getDay() === 1) {
                        strWeek = '<div class="calday raw-day kw"><div class="small-show">' + window.langStrings.calweek + ': </div>' +
                            k.getWeek() +
                            addSmallNavigation();
                        monthObj.append('<div class="break"></div>');
                        monthObj.append(strWeek);
                    }
                }
                // Last day
                addDayElement(k, monthObj, false);

                createNextMonth(monthObj);
                injectPeriodClassCalendar(k);
                el.append('<div class="break"></div>');
                window.Reservation.addReservations(k);
            },
            createTimeLine = function () {
                var periods,
                    s = window.settings.setting_calendar_start.split(' ')[0].split('-'),
                    e =  window.parseInt(window.settings.setting_calendar_duration, 10),
                    tl,
                    //start = new Date(Date.UTC(s[0], (s[1] - 1), s[2], 0, 0, 0, 0)),
                    start = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
                    //lastMonth = new Date(Date.UTC(s[0], (s[1] - 1), s[2], 0, 0, 0, 0)),
                    lastMonth = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
                    //end = new Date(Date.UTC(s[0], (s[1] - 1), s[2], 0, 0, 0, 0)),
                    end = new Date(s[0], (s[1] - 1), s[2], 0, 0, 0, 0),
                    monthStr,
                    oldMonthStr,
                    findP = function (obj, prop, v) {
                        return $.grep(obj, function (p) {
                            return p[prop].indexOf(v) > -1;
                        });
                    },
                    tt = [];
                end.setFullYear(start.getFullYear() + e);
                end.setMonth(11);
                $.ajax({
                    type: 'GET',
                    url: 'periods/all',
                    success: function (data) {
                        periods = data;
                        tl = $('#timeliner');
                        tl.html('');
                        while (start <= end) {
                            monthStr = start.getFullYear() + '-' + window.smallerThenTen(start.getMonth() + 1);
                            tl.append('<div id="tl-' + monthStr + '" class="graph-unit-container cal-unit-container"><div class="graph-unit-day cal-unit-day">' + window.showDate(start, 'short') + '</div></div>');
                            tl.css('width', tl.width() + ($('#tl-' + monthStr).outerWidth() + 2));
                            tt = findP(periods, 'period_start_new', monthStr);
                            if (tt.length === 0) {
                                lastMonth.setMonth(start.getMonth());
                                lastMonth.setFullYear(start.getFullYear());
                                lastMonth.setMonth(lastMonth.getMonth() - 1);
                                oldMonthStr = lastMonth.getFullYear() + '-' + window.smallerThenTen(lastMonth.getMonth() + 1);
                                tt[0] = {
                                    clan_code: $('#tl-' + oldMonthStr).attr('data_clan'),
                                    clan_description: $('#tl-' + oldMonthStr).children('span').text()
                                };
                            }
                            $('#tl-' + monthStr)
                                .addClass(tt[0].clan_code + '-solid')
                                .attr('data_clan', tt[0].clan_code)
                                .append('<span style="text-align: center; display: block">' + tt[0].clan_description + '</span>');
                            start.setMonth(start.getMonth() + 1);
                        }
                        var todayScrollDate = new Date(window.parseInt(window.localStorage.getItem('currentCalendarDate'), 10)),
                            ftw = $('#tl-' + todayScrollDate.getFullYear() + '-' + window.smallerThenTen(window.parseInt(todayScrollDate.getMonth(), 10) + 1)),
                            ftwPos = ftw.offset(),
                            realPos = ftwPos.left - ($('#timeliner-container').width() / 2);
                        $('#timeliner-container').scrollTo(realPos, 0);
                        ftw.css({background: '#ccc', border: '2px solid #b7282e', height: '44px'});
                    }
                });
            },

            /**
             * void
             */
            createYearsGrid = function () {
                var startDate = new Date(),
                    today = new Date(),
                    lastPrevDate = new Date(startDate),
                    lastNextDate = new Date(lastPrevDate),
                    opts = {
                        month: "long"
                    },
                    yearSelect = window.createYearList(),
                    monthSelect = [],
                    globalCheck,
                    i = window.parseInt(window.settings.setting_calendar_start.split('-')[0], 10),
                    j = new Date(),
                    k = j.getFullYear(),
                    htmlStr = '';

                j.setMonth(0);
                j.setDate(1);
                do {
                    monthSelect.push(window.monthNames[j.getMonth()]);
                    j.setMonth(j.getMonth() + 1);
                } while (j.getMonth() <= 11 && j.getFullYear() === k);
                if (defaults.clickedDate.getMonth() === 0) {
                    defaults.calendarWeek = 0;
                }
                instance.html('');
                globalCheck = checkGlobalCal(defaults.clickedDate);
                // Set next/prev month for display
                lastPrevDate.setMonth(defaults.clickedDate.getMonth() - 1);
                lastNextDate.setMonth(defaults.clickedDate.getMonth() + 1);
                // users reservations
                htmlStr += '<div id="inner_calendar" class="year-label">' +
                    '<div class="row">' +
                        '<div class="col-xs-12 col-sm-12 col-md-12"><h3 style="text-align: center">' +
                            window.langRes.periods +
                        '</h3></div>' +
                    '</div>' +
                    '<div class="row">' +
                        '<div class="col-xs-12 col-sm-12 col-md-12">' +
                            '<div id="timeliner-container">' +
                                '<div id="timeliner">' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="year-label-title row">' +
                        '<div class="cont-cal col-xs-12 col-sm-12 col-md-12"><h3 style="text-align: center">' + window.langRes.your_title + '</h3>' +
                            '<select class="form-control" name="users_res" id="users_res">' +
                            '</select>' +
                        '</div>' +
                    '</div>';
                // container
                htmlStr += '<div class="year-label" id="year_' + defaults.clickedDate.getFullYear() + '">';
                // today button
                htmlStr += '<div class="year-label-title row">' +
                        '<div class="cont-cal col-xs-12 col-sm-12 col-md-12">' +
                            '<button style="width: 100%" id="btntoday_' + startDate.getMonth() + '_' + startDate.getFullYear() + '" class="btn btn-default">' +
                                window.todayIs + ' ' + window.showDate(today, 'nozero') +
                            '</button>' +
                        '</div>' +
                    '</div>';
                // year buttons
                htmlStr += '<div class="year-label-title row">' +
                        '<div class="cont-cal col-xs-3 col-sm-4 col-md-4">' +
                            '<button id="e-yearprevious_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="btn btn-default bt-right" ' + globalCheck.year_prev + '>' +
                            '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>' +
                            '</button>' +
                        '</div>' +
                        '<div class="cont-cal col-xs-6 col-sm-4 col-md-4">' +
                            '<select id="_currentyear" class="form-control"></select>' +
                        '</div>' +
                        '<div class="cont-cal col-xs-3 col-sm-4 col-md-4">' +
                            '<button id="e-yearnext_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="btn btn-default bt-left" ' + globalCheck.year_next + '>' +
                            '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>' +
                            '</button>' +
                        '</div>' +
                    '</div>';
                instance.append(htmlStr);
                // month buttons
                htmlStr = '<div id="month_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="year-label-title row">' +
                    '<div class="cont-cal months col-xs-3 col-sm-4 col-md-4">' +
                        '<button id="e-monthprevious_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="btn btn-default bt-right" ' + globalCheck.month_prev + '>' +
                        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>' +
                        '</button>' +
                    '</div>' +
                    '<div class="cont-cal months col-xs-6 col-sm-4 col-md-4">' +
                        '<select id="_currentmonth" class="form-control"></select>' +
                    '</div>' +
                    '<div class="cont-cal months col-xs-3 col-sm-4 col-md-4">' +
                        '<button id="e-monthnext_' + defaults.clickedDate.getMonth() + '_' + defaults.clickedDate.getFullYear() + '" class="btn btn-default bt-left" ' + globalCheck.month_next + '>' +
                        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>' +
                        '</button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="year-label-title row">' +
                        '<div class="cont-cal col-xs-12 col-sm-12 col-md-12">' +
                        '<button style="width: 100%" id="reset_res" class="btn btn-default hundertpro">' +
                        window.langRes.chosen_dates +
                        ' ' +
                        window.langStrings.reset +
                        '</button>' +
                        '</div>' +
                        '<div class="cont-cal col-xs-12 col-sm-12 col-md-12">' +
                        '<button disabled="disabled" id="choosenDates" class="btn btn-default cont-cal hundertpro">' +
                        window.langRes.no_chosen_dates +
                        '</button>' +
                        '</div>' +
                    '</div>';


                $('#year_' + defaults.clickedDate.getFullYear()).append(htmlStr);

                // Selects
                window.fillSelect($('#_currentyear'), yearSelect, true);
                window.fillSelect($('#_currentmonth'), monthSelect, false);
                window.fillSelect($('#users_res'), window.userRes, false);
                createMonthGrid($('#year_' + defaults.clickedDate.getFullYear()));
                $('#_currentyear').val(defaults.clickedDate.getFullYear());
                $('#_currentmonth').val(defaults.clickedDate.getMonth());
                $('[data-toggle="popover"]').popover({
                    html: true
                });

                instance.append('</div>');
                createTimeLine();
            },

            /**
             *
             * @param obj jQuery object
             * @param isplus boolean plus one or minus one
             */
            navigateCalendar = function (obj, isMonth, isplus, isChosen) {
                var data = obj.split('_'),
                    year = parseInt(data[1], 10),
                    month = parseInt(data[0], 10),
                    clickDate = new Date(Date.UTC(year, month, 1, 0, 0, 0, 0));
                clickDate = new Date(year, month, 1, 0, 0, 0, 0);
                if (!isChosen) {
                    if (isMonth === 'y') {
                        if (isplus) {
                            clickDate.setFullYear(clickDate.getFullYear() + 1);
                        } else {
                            clickDate.setFullYear(clickDate.getFullYear() - 1);
                        }
                    }
                    if (isMonth === 'm') {
                        if (isplus) {
                            clickDate.setMonth(clickDate.getMonth() + 1);
                        } else {
                            clickDate.setMonth(clickDate.getMonth() - 1);
                        }
                    }
                }

                defaults.clickedDate = clickDate;

                createYearsGrid();
                $('#_currentyear').val(defaults.clickedDate.getFullYear());
                $('#_currentmonth').val(defaults.clickedDate.getMonth());
                $('#users_res').val(window.localStorage.getItem('user_res'));
                window.setCurrentCalendarDate(defaults.clickedDate);

                window.Reservation.setStyle();
                $('#_currentyear').val(defaults.clickedDate.getFullYear());
                $('#_currentmonth').val(defaults.clickedDate.getMonth());
            };
        this.createCalendar = function () {
            createYearsGrid();
        };

        /**
         * events
         */
        $(window).bind('resize', function (e) {
            var res = window.resizeEvt;
            $(window).resize(function () {
                clearTimeout(res);
                window.resizeEvt = setTimeout(function () {
                }, 500);
            });
        });

        jQuery(document).on('click', '#reset_res', function () {
            window.localStorage.removeItem('startDate');
            window.localStorage.removeItem('endDate');
            $(window.calDayId).removeClass(window.Reservation.addedReservationCss);
            $(window.calDayId).removeClass(window.Reservation.firstAddedReservationCss);
            $(window.calDayId).removeClass(window.Reservation.lastAddedReservationCss);
            $('#choosenDates')
                .html(window.langRes.no_chosen_dates)
                .addClass('btn-default')
                .removeClass('btn-success');
            $('#reset_res')
                .addClass('btn-default')
                .removeClass('btn-success');
            $('.reserv-btn').html('');
            $('#main-nav').slideDown(50);
            $('#editReservMenu').slideUp(50);
            $('#wrap').stop().animate({
                top: 0
            }, 'slow');
        });

        jQuery(document).on('click', '#reset_res_inline', function (e) {
            e.preventDefault();
            window.localStorage.removeItem('startDate');
            window.localStorage.removeItem('endDate');
            $(window.calDayId).removeClass(window.Reservation.addedReservationCss);
            $(window.calDayId).removeClass(window.Reservation.firstAddedReservationCss);
            $(window.calDayId).removeClass(window.Reservation.lastAddedReservationCss);
            $('#choosenDates')
                .html(window.langRes.no_chosen_dates)
                .addClass('btn-default')
                .removeClass('btn-success');
            $('#reset_res')
                .addClass('btn-default')
                .removeClass('btn-success');
            $('.reserv-btn').html('');
            return false;
        });

        jQuery(document).on('mouseover touchstart', '.day-date', function () {
            var ins = $(this),
                p = ins.parent('[id^="todaysDate_"]').attr('id').split('_');
            $('#data_' + p[1] + '_' + p[2] + '_' + p[3]).stop().slideUp(150);
            ins.children('.free-bed').children('.free-beds-more').stop().slideDown(500);
            return false;
        });
        jQuery(document).on('mouseout touchend', '.day-date', function () {
            var ins = $(this),
                p = ins.parent('[id^="todaysDate_"]').attr('id').split('_');
            $(this).children('.free-bed').children('.free-beds-more').stop().slideUp(500);
            $('#data_' + p[1] + '_' + p[2] + '_' + p[3]).stop().slideDown(150);
            return false;
        });

        jQuery(document).on('click touchstart', '#show-periods', function (e) {
            var timeliner = $('#timeliner');
            $(this).text(window.langRes.periods + ' ' + window.langDialog.hide_it);
            if (timeliner.children().length > 0) {
                $(this).text(window.langRes.periods + ' ' + window.langDialog.show_it);
                timeliner.html('');
                return false;
            }
            createTimeLine();
        });
        jQuery(document).on('change', '#users_res', function (e) {
            e.preventDefault();
            if ($(this).val() === '' || $(this).val() === null ||  $(this).val() === 'xxx') {
                return false;
            }
            var v = $(this).val(),
                res_id = v.split('|')[0],
                m = v.split('|')[1];
            window.localStorage.setItem('user_res', $(this).val());

            navigateCalendar(m, false, false, false);
            $(this).val(v);
        });

        jQuery(document).on('click', '#choosenDates', function (e) {
            e.preventDefault();
            if (window.localStorage.getItem('startDate') !== null) {
                var id = jQuery(this).attr('name').split('_'),
                    prevM = id[0] + '_' + id[1],
                    isMonth = (id[0].indexOf('month') > 0) ? 'm' : 'y';
                navigateCalendar(prevM, isMonth, true, true);
            }
        });

        jQuery(document).on('click', '[id^="e-yearprevious"], [id^="e-monthprevious"]', function () {
            checkEvents();
            if (jQuery(this).hasClass('false')) {
                return false;
            }
            var id = jQuery(this).attr('id').split('_'),
                prevM = id[1] + '_' + id[2],
                isMonth = (id[0].indexOf('month') > 0) ? 'm' : 'y';
            navigateCalendar(prevM, isMonth, false);
        });

        jQuery(document).on('click', '[id^="tl-"]', function (e) {
            checkEvents();
            var thisId =  jQuery(this).attr('id'),
                id = thisId.split('-'),
                prevM = window.smallerThenTen((id[2] - 1)) + '_' +  id[1],
                isMonth = (id[0].indexOf('month') > 0) ? 'm' : 'y',
                posChild = $('#' + thisId).index(),
                widthChild = window.parseInt($('#' + thisId).innerWidth(), 10);
            navigateCalendar(prevM, isMonth, false, true);
            $('#timeliner-container').scrollLeft(widthChild * posChild);
        });

        jQuery(document).on('click', '[id^="e-yearnext"], [id^="e-monthnext"]', function () {
            checkEvents();
            if (jQuery(this).hasClass('false')) {
                return false;
            }
            var id = jQuery(this).attr('id').split('_'),
                prevM = id[1] + '_' + id[2],
                isMonth = (id[0].indexOf('month') > 0) ? 'm' : 'y';
            navigateCalendar(prevM, isMonth, true);
        });

        jQuery(document).on('change', '[id^="_current"]', function () {
            var ids = $('[id^="_current"]');
            checkEvents();
            if (jQuery(this).hasClass('false')) {
                return false;
            }
            navigateCalendar(ids[1].value + '_' + ids[0].value, false);
        });
        jQuery(document).on('click', '.btn-right', function () {
            $('[id^="e-monthnext_"]').trigger('click');
        });
        jQuery(document).on('click', '.btn-left', function () {
            $('[id^="e-monthprevious_"]').trigger('click');
        });
        jQuery(document).on('click', '.btn-bottom', function () {
            $('body').animate({ scrollTop: $(document).height() }, "slow");
        });
        jQuery(document).on('click', '[id^="btntoday_"]', function () {
            checkEvents();
            var cd = jQuery(this).attr('id').split('_');
            defaults.clickedDate.setFullYear(cd[2]);
            defaults.clickedDate.setMonth(cd[1]);
            defaults.clickedDate.setDate(1);
            createYearsGrid();
            $('#_currentyear').val(defaults.clickedDate.getFullYear());
            $('#_currentmonth').val(defaults.clickedDate.getMonth());

        });
        jQuery(document).on('click touch touchstart', window.calDayId, function (e) {
            var self = $(this),
                selfId = self.attr('id'),
                daTi = self.attr('data_time');
            if ($('.popover').is(':visible')) {
                $('.popover').hide();
            }
            if (e.target.tagName === 'IMG') {
                $(e.target).popover('toggle');
                return false;
            }
            if ($(e.target).hasClass('day-date')) {
                return false;
            }

            window.eventCounter += 1;
            window.Reservation.countClicks(selfId);
            if ($('#editReservMenu').is(':visible')) {
                $('#reservation_guest_started_at, #reservation_guest_ended_at').trigger('change');
            }
        });
        return this.createCalendar();
    };

}(jQuery));
