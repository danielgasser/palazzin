/**
 * Created by daniel on 2/19/16.
 */

var graphDaySeries = [],
    prepareDataTable = function (years, data) {
        'use strict';
        var i,
            j,
            k,
            month,
            dataHtmlTable = $('#datatable-div'),
            dataHtml,
            theYear,
            dataHtmlHead = '',
            daysString = '';
        dataHtmlTable.html('');
        for (j = 1; j <= 31; j += 1) {
            k = j + 1;
            if (k > 31) {
                k = 1;
            }
            dataHtmlHead += '<th style="text-align: center; background-color: white !important;"><h6 style="font-size: 16px;">' + j + '-<br>' + k + '</h6></th>';
        }
        for (i = 0; i < years.length; i += 1) {
            theYear = window.parseInt(years[i], 10);
            if (data[theYear] === undefined) {
                continue;
            }
            dataHtmlTable.append('<table autosize="1" width="547" style="font-size: 16px !important; border-collapse: collapse; width: 547px" cellpadding="0" cellspacing="0" id="datatable_' + theYear + '" class="datatable_month table table-striped table-hover tablesorter table-stats"></table><pagebreak />');
            //dataHtmlTable.append('<pagebreak />');
            dataHtml = $('#datatable_' + theYear);
            dataHtml.append('<thead><tr id="datatable-head-year_' + theYear + '"></tr><tr id="datatable-head_' + theYear + '"></tr></thead>');
            $('#datatable-head-year_' + theYear).append('<th colspan="32" style="text-align: center;font-weight: bold; height: 40px; background-color: white !important; vertical-align: middle">' + theYear + '</th>');
            $('#datatable-head_' + theYear).append('<th style="background-color: white !important;"></th>');
            $('#datatable-head_' + theYear).append(dataHtmlHead);
            dataHtml.append('<tbody style="" id="year_' + theYear + '"></tbody>');
            for (month = 1; month <= 12; month += 1) {
                daysString = '';
                for (j = 1; j <= 31; j += 1) {
                    k = j + 1;
                    if (k > 31) {
                        k = 1;
                    }
                    daysString += '<td style="width: 12px; text-align: center;" id="' + theYear + '_' + window.smallerThenTen(month) + '_' + window.smallerThenTen(j) + '"></td>';
                }
                $('#year_' + theYear).append('<tr id="theMonth_' + theYear + '_' + window.smallerThenTen(month) + '"><td>' + window.langCalendar[month - 1] + '</td>' + daysString + '</tr>');
            }
        }
    },
    fillTableShort = function (data_month, data_year, years) {
        'use strict';
        var i,
            theYear = 0,
            showYear = [],
            ele = $('#data-short'),
            monthcounter,
            chart,
            graphTotalSeries = {},
            highChartOptionsTotal = {
                chart: {
                    type: 'column',
                    renderTo: 'chart_div_total'
                },
                title: {
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    categories: window.langCalendar,
                    labels: {
                        style: {
                            fontSize: '16px'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Nächte'
                    },
                    labels: {
                        style: {
                            fontSize: '16px'
                        }
                    }
                },
                tooltip: {
                    hideDelay: 500
                }
            };
        if (data_month.length === 0) {
            $('#chart_div_total').html('<b>Keine Daten</b>');
        } else {
            $('#datatable-short>thead').show();
            ele.html('');
            for (i = 0; i < years.length; i += 1) {
                theYear = window.parseInt(years[i], 10);
                ele.append('<tr id="short_year_' + theYear + '"></tr>');
                $('#short_year_' + theYear).append('<td style="color: ' + window.yearColors[theYear] + ' !important">' + theYear + '</td>');
                for (monthcounter = 1; monthcounter < 13; monthcounter += 1) {
                    $('#short_year_' + theYear).append('<td style="color: ' + window.yearColors[theYear] + ' !important" id="' + theYear + '_' + window.smallerThenTen(monthcounter) + '"></td>');
                }
                $('#short_year_' + theYear).append('<td style="color: ' + window.yearColors[theYear] + ' !important" id="totals_year_' + theYear + '"></td>');
            }
            $.each(data_month, function (i, n) {
                $('#' + i).html(n);
            });
            $.each(data_year, function (i, n) {
                $('#totals_year_' + i).html(n);
            });
            $.each($('[id^="short_year_"]'), function (i, n) {
                var propYear = $(n).children(":first").html();
                if (data_year[propYear] === undefined) {
                    return false;
                }
                showYear.push(propYear)
                if (Object.keys(data_month).some(function (k) { return ~k.indexOf(propYear) })) {
                    $('#year_' + propYear).attr('data-empty', 'full');
                } else {
                    $('#year_' + propYear).attr('data-empty', 'empty');
                }
                graphTotalSeries[propYear] = [];
                $.each($(n).children(), function (j, m) {
                    var valN = (isNaN(window.parseInt($(m).html(), 10))) ? 0 : window.parseInt($(m).html(), 10);
                    graphTotalSeries[propYear].push(valN);
                });
                graphTotalSeries[propYear].splice(0, 1);
                graphTotalSeries[propYear].splice(-1, 1);
            });
            if (chart !== undefined) {
                chart.destroy();
            }
            chart = new window.Highcharts.Chart(highChartOptionsTotal);
            chart.setTitle({
                text: 'Logiernächte Total ' + showYear.join(', ')
            });
            $('#chart_div_total').append('<pagebreak />');
            $.each(graphTotalSeries, function (i, n) {
                var color = window.yearColors[i];
                chart.addSeries({
                    data: n,
                    name: i,
                    color: color
                });
            });
            window.all_charts.push(chart);
        }
        $('[data-empty="empty"]').hide();
    },
    fillTable = function (data, years) {
        'use strict';
        var dataHtml = $('#data'),
            chart,
            highChartOptionsDay = {
                chart: {
                    type: 'column',
                    renderTo: 'chart_div',
                    height: 250
                },
                legend: {
                    enabled: false
                },
                title: {
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    tickInterval: 1,
                    pointInterval: 1,
                    min: 1,
                    title: {
                        text: 'Tage'
                    },
                    labels: {
                        step: 1,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yAxis: {
                    tickInterval: 1,
                    pointInterval: 1,
                    min: 0,
                    max: 15,
                    categories: window.monthDays,
                    title: {
                        text: 'Logier-Nächte'
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                tooltip: {
                    headerFormat: ' ',
                    pointFormat: 'Logier-Nächte am {point.x} <b>{point.y}</b>',
                    hideDelay: 500
                }
            },
            colorCounter = 0;
        if (data[0].length === 0) {
            $('#chart_div').html('');
        } else {
            dataHtml.html('');
            prepareDataTable(years, data[2]);
            var jj = 0;
            if (chart !== undefined) {
                chart.destroy();
            }
            $.each(years, function (c, year) {
                var month,
                    day,
                    ele,
                    y = window.parseInt(year, 10);
                if (data[2][y] === undefined) {
                    return false;
                }
                window.all_charts.push(chart);
                for (month = 1; month < 13; month += 1) {
                    $('#chart_div').append('<div style="margin-bottom: 12px; width: 33%; float: left;" id="chart_year_month_' + y + '_' + month + '"></div>');
                    highChartOptionsDay.chart.renderTo = 'chart_year_month_' + y + '_' + month;
                    highChartOptionsDay.tooltip.pointFormat = 'Logier-Nächte am {point.x}.' + window.langCalendar[month - 1] + ' ' + y + ': <b>{point.y}</b>';
                    chart = new window.Highcharts.Chart(highChartOptionsDay);
                    chart.setTitle({
                        text: 'Logiernächte ' + window.langCalendar[month - 1] + ' ' + y
                    });
                    graphDaySeries = [];
                    for (day = 0; day < 32; day += 1) {
                        ele = y + '_' + window.smallerThenTen(month) + '_' + window.smallerThenTen(day);
                        $('#' + ele).html(data[0][ele]);
                        if (data[0][ele] === undefined) {
                            graphDaySeries.push(0);
                        } else {
                            graphDaySeries.push(data[0][ele]);
                        }
                    }
                    colorCounter += 1;
                    chart.addSeries({
                        data: graphDaySeries,
                       // name: null,
                        color: window.yearColors[y]
                    });
                    window.all_charts.push(chart);

                }
            });
        }
        fillTableShort(data[1], data[2], years);
    };
