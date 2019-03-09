var distinctArray = function (arr) {
        var newArray = [];
        for(var i=0, j=arr.length; i<j; i++){
            if(newArray.indexOf(arr[i]) == -1)
                newArray.push(arr[i]);
        }
        return newArray;
},fillTable = function (data, years, family_sum, guest_sum) {
    'use strict';
    var htmlString = '',
        i,
        y,
        yy = [],
        fam_sum = (family_sum !== undefined) ? family_sum : null,
        g_sum = (guest_sum !== undefined) ? guest_sum : null,
        total_nights = null,
        total_family = null,
        family_props = null,
        total_nights_guest = {},
        total_guests = null,
        year,
        the_years = years,
        p,
        props = [],
        guest_props = [],
        last = window.route.substr(window.route.lastIndexOf('/') + 1),
        further_url = window.route.replace(last, '');
    $.ajax({
        type: 'GET',
        url: further_url + 'stats_chron_guest_night_total',
        data: {
            year: the_years
        },
        success: function (d) {
            total_nights = d.total_family[1];
            total_family = d.total_family[0];
            family_props =  d.total_family[2];
            guest_props = d.guest_props;
            total_guests = d.total_guests;
            total_nights_guest = d.total_year_guests;
            fillFamilyCakeStats(total_family, yy, 'chart_div_three', total_nights, 'Übernachtungen pro Halbstamm ', 'pie_nights_', family_props);
            if (total_family !== null) {
                fillBarStats(fam_sum, g_sum, yy, 'chart_div');
            }
            if (total_guests !== null) {
                fillBarStats(g_sum, g_sum, yy, 'chart_div_two', guest_props);
            }
            fillFamilyCakeStats(total_guests, yy, 'chart_div_four', total_nights_guest, 'Übernachtungen pro Art des Gastes ', 'pie_guest_nights_', guest_props);
        }
    });
    $('#datatable').html('');
    $('#datatable').append('<thead><tr><th><h6>Monat</h6></th><th><h6>Name/Vorname</h6></th><th><h6>Stamm</h6></th><th><h6>Halb-Stamm</h6></th><th><h6>Ankunft</h6></th><th><h6>Abreise</h6></th><th><h6>Nächte</h6></th><th><h6>Rechnungs<br>betrag</h6></th><th><h6>Gäste</h6></th></tr></thead>');
    for (i = 0; i < years.length; i += 1) {
        y = window.parseInt(years[i], 10);
        if (i > 0) {
            $('#datatable').append('<pagebreak />')
        }
        if(family_sum[y] === undefined) {
            continue;
        }
        $('#datatable').append('<tbody id="cron_year_' + y + '"><tr><td style="font-weight: bold; text-align: center; border-top: 1px solid #18130c;" colspan="9">' + y + '</td></tr></tbody>');
        yy.push(y);
    }
    $.each(data, function (i, n) {
        htmlString = '';
        htmlString += '<tr>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += window.langCalendar[(n.reservation_started_at_month - 1)] + '\'' + n.reservation_started_at_year;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.user_first_name + ' ' + n.user_name;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.clan_description;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.family_description;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.reservation_started_at_show;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.reservation_ended_at_show;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        htmlString += n.reservation_nights;
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top">';
        if (n.bill !== undefined && n.bill.length > 0) {
            $.each(n.bill, function (j, m) {
                htmlString += m.bill_total;
            });
        } else {
            htmlString += '-';
        }
        htmlString += '</td>';
        htmlString += '<td style="vertical-align: top"><div class="guests">';
        if (n.guest !== undefined && n.guest.length > 0) {
            $.each(n.guest, function (j, m) {
                var endline = (j === (n.guest.length - 1)) ? '' : '<br>';
                htmlString += m.guest_number + ' x ' + m.role_description + endline;
            });
            htmlString += '<hr>' + n.guest_sum + ' Gäste Total';
        } else {
            htmlString += 'Keine Gäste';
        }
        htmlString += '</div></td>';
        htmlString += '</tr>';
        $('#cron_year_' + n.reservation_started_at_year).append(htmlString);
    });
},
    fillFamilyCakeStats = function (nights, yearLabel, el, t_nights, title, pie, props) {
        var options;
        $('#' + el).html('');
        $.each(yearLabel, function (i, y) {
            if (props === undefined && nights instanceof Array) {
                props = Object.getOwnPropertyNames(nights[y]);
            }
            if (nights[y] === undefined) {
                return false;
            }
            $('#' + el).append('<div id="' + pie + y + '" style="min-width: 310px; height: 400px; margin: 20px auto;"></div>');
            options = {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    renderTo: pie + y
                },
                title: {
                    text: title + y
                },
                subtitle: {
                    text: 'Total Übernachtungen ' + y + ': ' + t_nights[y],
                    style: {
                        fontSize: '18px'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: false,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                                fontSize: '18px',
                                lineHeight: '20px'
                            },
                            formatter: function() {
                                return '<h6>'+ this.point.name +': ' + (Math.round(this.point.percentage * 10) / 10) + '% = ' + this.y +' Nächte</h6>';
                            }
                        }
                    }
                },
                series: [{
                    name: 'Anteil',
                    colorByPoint: true,
                    data: []
                }]
            };
            if (props !== undefined) {
                $.each(props, function (i, p) {
                    var v = isNaN(parseInt(nights[y][p], 10)) ? 0 : parseInt(nights[y][p], 10);
                    options.series[0].data.push(
                        {
                            name: p,
                            y: parseInt(v, 10),
                            color: $('.total').css('color'),
                            fontSize: '18px'
                        }
                    )
                });
            }
            $(function () {
                var chart = new window.Highcharts.Chart(options);
                options = {};
            });
        });

    },
    fillBarStats = function (family, guest, yearLabel, el, props) {
        var title,
            yl = [],
            options;
        if (el === 'chart_div') {
            title = 'Reservierungen pro Halbstamm ';
        } else {
            title = 'Reservierungen pro Art des Gastes ';
        }

        options = {
            chart: {
                type: 'bar',
                renderTo: el
            },
            title: {
                text: title
            },
            xAxis: {
                categories: '',
                title: {
                    text: null
                }},
            yAxis: {
                min: 0,
                tickInterval: 1,
                title: {
                    text: 'Anzahl Reservierungen',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
            },
            series: []
        };
        $.each(yearLabel, function (i, n) {
            var dats = [];
            if (family[n] === undefined) {
                return false;
            }
            if (props === undefined) {
                props = Object.getOwnPropertyNames(family[n])
            }
            yl.push(n);
            $.each(props, function (j, m) {
                if (family[n] !== undefined || family[n][m] !== undefined) {
                    dats.push((isNaN(parseInt(family[n][m], 10))) ? 0 : parseInt(family[n][m], 10));
                } else {
                    dats.push(0);
                }
            });
            options.xAxis.categories = props;
            options.series.push(
                {
                    name: n,
                    data: dats,
                    color: window.yearColorsSet[i],
                    fontSize: '18px'
                }
            );
            options.title.text = title + yl.join(', ');
        });
        $(function () {
            var chart = new window.Highcharts.Chart(options)
            options = {};
        });

    },
    y = $('#year');
$(document).ready(function(){
    'use strict';
    $('[name^="year"]').on('change', function (event, state) {
        window.checkedYear = [];
        $.each($('[name^="year"]'), function (i, n) {
            if ($(n).is(':checked')) {
                window.checkedYear.push(n.value + '-%');
            }
        });
        window.checkedYear.sort();
        window.showYear.sort();
    })
})
$(document).on('click', '#getYears', function () {
    if (window.checkedYear.length === 0) {
        $('[name^="year"]').trigger('change');
    }
    window.getStatsData('/stats_chron', window.checkedYear, window.fillTable);
});
