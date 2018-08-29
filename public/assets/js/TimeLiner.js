/**
 * jQuery Plugin frame
 */
/*jslint todo: true */

(function ($) {
    "use strict";
    $.fn.timeLiner = function (options) {
        var settings = $.extend({
                startDate: null,
                endDate: null,
                showObject: null
            }, options),
            graphHeight = 10,
            cssId,
            userNameId,
            dbDate,
            obj,
            lastEntry = '',
            daysInMonth = function (month, year) {
                //return new Date(Date.UTC(year, month, 0)).getDate();
                return new Date(year, month, 0).getDate();
            };
        $.each($(settings.showObject), function () {
            $(this).html('');
        });
        return this.each(function (i, n) {
            var sd = (settings.startDate === null) ? [] : settings.startDate.split('.'),
                ihi = 0,
                t,
                opts = {
                    month: 'long'
                },
                graphHeightContainer = [],
                ed = (settings.startDate === null) ? [] : settings.endDate.split('.'),
                //startDate = (settings.startDate === null) ? new Date() : new Date(Date.UTC(sd[2], sd[1], sd[0], 0, 0, 0, 0, 0)),
                startDate = (settings.startDate === null) ? new Date() : new Date(sd[2], sd[1], sd[0], 0, 0, 0, 0, 0),
                endDate;
            startDate.setDate(1);
            if (settings.endDate === null) {
                endDate = startDate;
                endDate.setMonth(endDate.getMonth() + 1);
                endDate.setDate(0);
            } else {
                //endDate = new Date(Date.UTC(ed[1], ed[0], 0, 0, 0, 0, 0));
                endDate = new Date(ed[1], ed[0], 0, 0, 0, 0, 0);
            }

            obj = $(settings.showObject)[i];
            while (startDate <= endDate) {
                userNameId = n.id;
                dbDate = startDate.getFullYear() + '-' + window.smallerThenTen((startDate.getMonth() + 1)) + '-' + window.smallerThenTen(startDate.getDate());
                cssId = userNameId + startDate.getFullYear() + window.smallerThenTen((startDate.getMonth() + 1)) + window.smallerThenTen(startDate.getDate());
                $('<div></div>')
                    .prop({
                        'id': cssId,
                        'title': startDate.getDate() + '.' + (startDate.getMonth() + 1) + '.' + startDate.getFullYear()
                    })
                    .addClass('graph-unit-container')
                    .html('<div class="graph-unit-day">' + startDate.getDate() + '</div>')
                    .appendTo(obj);
                $('#' + cssId).append('<div style="height: 0" class="graph-unit" id="' + userNameId + startDate.getTime() + '"></div>')
                for (ihi in n.userDates) {
                    if (dbDate === n.userDates[ihi].updated_at) {
                        if (lastEntry === n.userDates[ihi].updated_at) {
                            graphHeight += 10;
                        } else {
                            graphHeight = 10;
                        }
                        lastEntry = n.userDates[ihi].updated_at;
                        $('#' + userNameId + startDate.getTime())
                            .css('height', parseInt(graphHeight / 3, 10) + 'px')
                            .prop('title', n.userDates[ihi].created_at + ' | ' + (graphHeight / 10) + ' Logins')
                            .html('<div class="graph-unit-logins-day">' + (graphHeight / 10) + '</div>');
                        $('#' + cssId).parent('.long-right').css('margin-top', window.parseInt(graphHeight / 40 + 0.5) + '%');
                        graphHeightContainer.push(graphHeight);
                    }
                    t = Math.max.apply(Math, graphHeightContainer);
                    $('#' + cssId).css('height', (parseInt(graphHeight / 2, 10) - 2) + 'px');
                }
                if (startDate.getDate() === daysInMonth((startDate.getMonth() + 1), startDate.getFullYear())) {
                    $('<div></div>')
                        .addClass('graph-month')
                        .html(window.showDate(startDate, 'month') + ' ' + startDate.getFullYear())
                        .appendTo(obj);
                }
                startDate.setDate(startDate.getDate() + 1);
            }
            $('<div></div>')
                .addClass('break')
                .appendTo(obj);

        });
    };
}(jQuery));
