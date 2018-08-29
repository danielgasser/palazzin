/**
 * Created by daniel on 2/19/16.
 */
fillTable = function (data, years) {
    'use strict';
    var htmlString = '',
        i,
        y,
        yy = [],
        t_nights = null,
        nf_sum = null,
        g_nights = {},
        g_sum_nights = null,
        year,
        the_years = years,
        p,
        users = [],
        clean_props = [],
        last = window.route.substr(window.route.lastIndexOf('/') + 1),
        further_url = window.route.replace(last, '');
    var options = {
        chart: {
            type: 'bar',
            renderTo: 'chart_div'
        },
        title: {
            text: 'Logindaten'
        },
        xAxis: {
            categories: '',
            title: {
                text: 'Benutzer'
            }},
        yAxis: {
            //min: 0,
            tickInterval: 10,
            title: {
                text: 'Anzahl Besuche',
                align: 'high'
            },
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
            x: 9,
            y: 80,
            floating: false,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        series: []
    };
    console.log(data, clean_props);
    $.each(data, function (i, n) {
        options.series.push({
            name: n.user_first_name + ' ' + n.user_name,
            data: [n.data],
            color: window.yearColorsSet[i],
            fontSize: '18px',
            ineWidth: 15
        })
    });

    $(function () {
        var chart = new window.Highcharts.Chart(options)
        //$('#' + el).highcharts(options);
        options = {};
    });};
