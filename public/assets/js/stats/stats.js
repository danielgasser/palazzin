/**
 * Created by daniel on 2/19/16.
 */
var getStatsData = function (url, year, callback) {
    'use strict';
    if (year.length === 0) {
        return false;
    }
    var yearLabel = [];
    $.each(year, function (i, n) {
        yearLabel.push(window.parseInt(n, 10));
    });
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            year: year
        },
        success: function (d) {
            if (url.indexOf('stats_chron') > -1) {
                callback(d[0], year, d[1], d[2], d[3]);
            } else {
                callback(d, year);
            }
            window.setTimeout(function () {
                $('#asPDF').show();
            }, 2000)
            $('#stats_title').text(yearLabel.join(', '));
        }
    });
};
$(document).on('click', '#asPDF', function () {
    'use strict';
    $('.highcharts-tooltip').hide();
    var tr = {
            'ä': 'ae',
            'ü': 'ue',
            'ö': 'oe',
            'ß': 'ss'
        },
        replaceUmlauts = function (s) {
            return s.replace(/[äöüß]/g, function ($0) {
                return tr[$0];
            });
        },
        title = $('#menu_stats>h1').text(),
        file_name = replaceUmlauts(title);
    file_name = file_name.replace(/ /gi, '_');
    file_name = file_name.replace(/[^a-zA-Z0-9_]/gi, '');
    $.ajax({
        type: 'POST',
        url: '/stats_print',
        data: {
            html: $('#mPDF_Print').html(),
            filename: file_name,
            title: title,
            dir: $('#asPDF').attr('data-direction'),
            font: $('#asPDF').attr('data-font')
        },
        success: function (d) {
            $('.highcharts-tooltip').show();
            window.open(
                d,
                '_blank'
            );
        }
    });


});


