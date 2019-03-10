var dataTableOptions = {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Kopieren',
                className: 'btn btn-default'
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-default'
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-default'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-default'
            },
            {
                extend: 'print',
                text: 'Drucken',
                className: 'btn btn-default'
            }
        ],
        searching: false,
        pageLength: 12,
        paging: false,
        sortable: false,
        ordering: false,
        language: {
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu
        },
        responsive: true,
        autoWidth: false,
        fixedHeader: {
            header: false,
            footer: false
        },
        columns: [
            {
                width: "76px",
            },
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ],
    },
    dataTableOptionsTotals = {
        columnDefs: [
            {
                targets: [0],
                visible: true,
                orderable: true,
                data: 'bill_no',
            },
            {
                targets: [1],
                visible: true,
                orderable: true,
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d_string = data.split('.'),
                            d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
            {
                targets: [2],
                visible: true,
                orderable: true,
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d_string = data.split('.'),
                            d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
            {
                targets: [3],
                visible: true,
                orderable: true,
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        let d_string = data.split('.'),
                            d = new Date(d_string[2], d_string[1], d_string[0], 0, 0, 0)
                        return d.getTime();
                    }
                    return data;
                }
            },
            {
                targets: [4],
                visible: true,
                orderable: true,
                type: 'numeric-comma',
                render: function (data, type, full, meta) {
                    if (type === 'sort') {
                        console.log(parseFloat(data.split(' ')[1]))
                        return parseFloat(data.split(' ')[1])
                    }
                    return data;
                }
            }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: 'Kopieren',
                className: 'btn btn-default'
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-default'
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-default'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-default'
            },
            {
                extend: 'print',
                text: 'Drucken',
                className: 'btn btn-default'
            }
        ],
        searching: true,
        paging: false,
        sortable: true,
        ordering: true,
        language: {
            emptyTable: 'Keine Daten vorhanden',
            paginate: {
                first: window.paginationLang.first,
                previous: window.paginationLang.previous,
                next: window.paginationLang.next,
                last: window.paginationLang.last
            },
            search: window.langDialog.search,
            info: window.paginationLang.info,
            sLengthMenu: window.paginationLang.length_menu
        },
        responsive: true,
        autoWidth: true,
        fixedHeader: {
            header: false,
            footer: false
        }
    },
    graphSeries = [],
    chart,
    fillMonthTable = function (data, years) {
        'use strict';
        var ele = $('#monthTable'),
            thead = '<thead>' +
                '<tr>' +
                '<th><h6>Zur Rechnung (PDF)</h6></th>' +
                '<th><h6>Rechnungsdatum</h6></th>' +
                '<th><h6>Reservationsdatum</h6></th>' +
                '<th><h6>Bezahlt am</h6></th>' +
                '<th><h6>Rechnungsbetrag</h6></th>' +
                '</tr>' +
                '</thead>';
        ele.html('');
        $.each(years, function (i, y) {
            let year = window.parseInt(y, 10);
            ele.append('<div style="font-weight: bold; text-align: center; border-top: 4px solid #18130c;"><h4>' + year + '</h4></div>')
            ele.append('<table id="datatable_' + year + '" class="table table-stats"></table>')
            if (i < years.length - 1) {
                ele.append('<pagebreak />');
            }
            $('#datatable_' + year)
                .append(thead)
                .append('<tbody id="sep_year_' + year + '">');
        });
        $.each(data, function (j, n) {
            var paid = (n.bill_paid !== null) ? n.bill_paid_show : '-';
            $('#sep_year_' + n.bill_bill_year).append('<tr>' +
                '<td><a target="_blank" href="' + n.bill_path + '">' + n.bill_no + '</a></td>' +
                '<td>' + n.bill_bill_date_show + '</td>' +
                '<td>' + n.reservation_started_at_show + '</td>' +
                '<td>' + paid + '</td>' +
                '<td>' + n.bill_currency + ' ' + n.bill_total + '</td>' +
                '</tr>');
        });
        $.each(years, function (i, y) {
            let year = window.parseInt(y, 10),
                el = '#datatable_' + year;
            if ($.fn.DataTable.isDataTable(el)) {
                $(el).destroy();
            }
            $(el).DataTable(dataTableOptionsTotals);
        });
    },
    fillTotalTotalTable = function (data, years) {
        'use strict';
        var graphTotalSeries = 0,
            graphPaidSeries = 0,
            graphUnpaidSeries = 0,
            table = '<table id="datatable-total" class="table table-stats">',
            yearLabel = [],
            totalAllYears,
            tableContainer = $('#total-total-tables'),
            paidAllYears,
            unpaidAllYears,
            show_totalAllYears,
            show_paidAllYears,
            show_unpaidAllYears,
            totals = data.totals;
        if (data.length === 0) {
            $('#datatable').hide();
        } else {
            $('#datatable').show();

        }
        $.each(years, function (i, n) {
            yearLabel.push(window.parseInt(n));
        });
        tableContainer.html('');
        table += '<thead>';
        table += '<tr>';
        table += '<th colspan="3" id="total_total_label"><h6>Total alle Jahre ' + yearLabel.join(', ') + '</h6></th>';
        table += '</tr>';
        table += '<tr>';
        table += '<th style="width: 33%"><h6>Total</h6></th>';
        table += '<th style="width: 33%"><h6>Bezahlt</h6></th>';
        table += '<th style="width: 33%"><h6>Unbezahlt</h6></th>';
        table += '</tr>';
        table += '</thead>';
        table += '<tbody id="data-short">';
        table += '<tr>';
        table += '<td class="paid" id="total_total"></td>';
        table += '<td class="total" id="total_paid"></td>';
        table += '<td class="unpaid" id="total_unpaid"></td>';
        table += '</tr>';
        table += '</tbody>';
        table += '</table><pagebreak />';
        tableContainer
            .append(table)
            .append('<div id="chart_total_total"></div>');
        if (totals !== undefined) {
            totalAllYears = (totals.total !== undefined) ? totals.total : 0;
            paidAllYears = (totals.paid !== undefined) ? totals.paid : 0;
            unpaidAllYears = (totals.unpaid !== undefined) ? totals.unpaid : 0;
            show_totalAllYears = (totals.total !== undefined) ? totals.total_show : '0.00';
            show_paidAllYears = (totals.paid !== undefined) ? totals.paid_show : '0.00';
            show_unpaidAllYears = (totals.unpaid !== undefined) ? totals.unpaid_show : '0.00';
            $('#total_total').html(show_totalAllYears);
            $('#total_paid').html(show_paidAllYears);
            $('#total_unpaid').html(show_unpaidAllYears);
            graphTotalSeries = window.parseFloat(totalAllYears);
            graphPaidSeries = window.parseInt(totalAllYears) / window.parseInt(unpaidAllYears);
            graphUnpaidSeries = window.parseInt(totalAllYears) / window.parseInt(paidAllYears);
            $(function () {
                var chart_year = $('#chart_total_total').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Total alle Jahre ' + yearLabel.join(', ')
                    },
                    tooltip: {
                        headerFormat: ' ',
                        pointFormat: '{point.name}: <b>{point.percentage:.1f} %</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: false,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                                    fontSize: '16px'
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Total Jahre ' + yearLabel.join(', '),
                        colorByPoint: true,
                        data: [
                            {
                                name: 'Bezahlt ' + yearLabel.join(', '),
                                y: graphPaidSeries,
                                color: '#333',
                                fontSize: '18px'
                            },
                            {
                                name: 'Unbezahlt ' + yearLabel.join(', '),
                                y: graphUnpaidSeries,
                                color: '#ff9900',
                                fontSize: '18px'
                            }]
                    }]
                });
                graphSeries = 0;
                graphTotalSeries = 0;
                graphPaidSeries = 0;
                graphUnpaidSeries = 0;
            });
        } else {
            $('#chart_total_total').html('<b>Keine Daten</b>');
            $('#datatable-total').hide();
        }
    },
    fillYearTotalTable = function (data, years) {
        'use strict';
        var graphTotalSeries = 0,
            graphPaidSeries = 0,
            graphUnpaidSeries = 0,
            chart,
            graphSeries,
            yearLabel = [];
        $.each(years, function (i, n) {
            yearLabel.push(window.parseInt(n));
        });
        $('#all-year-tables').html('');
        $.each(years, function (i, y) {
            var year = window.parseInt(y, 10),
                table = '<table autosize="1" id="datatable-year-' + year + '" class="table table-stats">',
                totalYear,
                paidYear,
                unpaidYear,
                show_totalYear,
                show_paidYear,
                show_unpaidYear;
            table += '<thead>';
            table += '<tr>';
            table += '<th colspan="3" id="total_year_label_' + year + '"><h6>Total Jahr ' + year + '</h6></th>';
            table += '</tr>';
            table += '<tr>';
            table += '<th style="width: 33%"><h6>Total</h6></th>';
            table += '<th style="width: 33%"><h6>Bezahlt</h6></th>';
            table += '<th style="width: 33%"><h6>Unbezahlt</h6></th>';
            table += '</tr>';
            table += '</thead>';
            table += '<tbody id="data-short">';
            table += '<tr>';
            table += '<td class="total" id="year_total_' + year + '"></td>';
            table += '<td class="paid" id="year_paid_' + year + '"></td>';
            table += '<td class="unpaid" id="year_unpaid_' + year + '"></td>';
            table += '</tr>';
            table += '<tr><td colspan="3" style="width: 547px"><div id="chart_year_total_' + year + '"></div></td>';
            table += '</tr>';
            table += '</tbody>';
            table += '</table><pagebreak />';
            $('#all-year-tables').append(table);
            totalYear = (data[year] !== undefined) ? data[year].total : 0;
            paidYear = (data[year] !== undefined) ? data[year].paid : 0;
            unpaidYear = (data[year] !== undefined) ? data[year].unpaid : 0;
            show_totalYear = (data[year] !== undefined) ? data[year].total_show : '0.00';
            show_paidYear = (data[year] !== undefined) ? data[year].paid_show : '0.00';
            show_unpaidYear = (data[year] !== undefined) ? data[year].unpaid_show : '0.00';
            $('#year_paid_' + year).html(show_paidYear);
            $('#year_total_' + year).html(show_totalYear);
            $('#year_unpaid_' + year).html(show_unpaidYear);
            graphTotalSeries = window.parseFloat(totalYear);
            graphPaidSeries = 100 / graphTotalSeries * window.parseFloat(paidYear);
            graphUnpaidSeries = 100 / graphTotalSeries * window.parseFloat(unpaidYear);

            if (graphTotalSeries > 0) {
                $(function () {
                    $('#chart_year_total_' + year).highcharts({
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: 'Total Jahr ' + year
                        },
                        tooltip: {
                            headerFormat: ' ',
                            pointFormat: '{point.name}: <b>{point.percentage:.1f} %</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: false,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}: {point.percentage:.1f} %',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                                        fontSize: '18px',
                                        fontWeight: 'bold'
                                    }
                                }
                            }
                        },
                        series: [{
                            name: year,
                            colorByPoint: true,
                            data: [
                                {
                                    name: 'Bezahlt ' + year,
                                    y: graphPaidSeries,
                                    color: '#333'
                                },
                                {
                                    name: 'Unbezahlt ' + year,
                                    y: graphUnpaidSeries,
                                    color: '#ff9900'
                                }]
                        }]
                    });
                    graphSeries = 0;
                    graphTotalSeries = 0;
                    graphPaidSeries = 0;
                    graphUnpaidSeries = 0;
                });
            } else {
                $('#chart_year_total_' + year).html('<b>Keine Daten fürs Jahr ' + year + '</b>');
                $('#datatable-year-' + year).hide();
            }
        });
        fillTotalTotalTable(data, years);
    },
    fillTable = function (data, years) {
        'use strict';
        var dataHtml = $('#datatable-short'),
            totalData = [],
            paidData = [],
            graphSeries = [],
            unpaidData = [],
            yearLabel = [],
            data_total = (data.total !== undefined) ? data.total : [],
            data_paid = (data.paid !== undefined) ? data.paid : [],
            data_unpaid = (data.unpaid !== undefined) ? data.unpaid : [];
        dataHtml.html('');
        $.each(years, function (i, n) {
            yearLabel.push(window.parseInt(n));
        });
        $('#data-total-short').html('');
        $('#charts').html('');
        $.each(years, function (j, y) {
            var ele,
                htmlStringMonth = '',
                htmlStringTotal = '',
                htmlStringPaid = '',
                htmlStringUnpaid = '',
                month,
                ele_month,
                amount,
                temp_amount,
                amountTotalValue,
                amountPaidValue,
                amountTUnpaidValue,
                totalTotalValue = 0,
                totalPaidValue = 0,
                totalUnpaidValue = 0,
                year = window.parseInt(y);
            dataHtml.append('<div id="charts_' + year + '"></div>');
            dataHtml.append('<table id="datatable-short_' + year + '" class="table table-stats"></table><pagebreak />');
            ele = $('#datatable-short_' + year);
            ele.append('<thead><tr id="month_' + year + '"><th>&nbsp;</th>');
            ele.append('<tbody id="data-total-short_' + year + '"></tbody>');
            htmlStringMonth += '';
            htmlStringTotal += '<tr id="total_' + year + '"><td class="total">Total</td>';
            htmlStringPaid += '<tr id="paid_' + year + '"><td class="paid">Bezahlt</td>';
            htmlStringUnpaid += '<tr id="unpaid_' + year + '"><td class="unpaid">Unbezahlt</td>';
            for (month = 1; month < 13; month += 1) {
                ele_month = year + '_' + GlobalFunctions.smallerThenTen(month);
                amountTotalValue = (data_total[ele_month] === undefined) ? '0.00' : data_total[ele_month];
                amountPaidValue = (data_paid[ele_month] === undefined) ? '0.00' : data_paid[ele_month];
                amountTUnpaidValue = (data_unpaid[ele_month] === undefined) ? '0.00' : data_unpaid[ele_month];

                htmlStringMonth += '<th>' + window.langCalendar[month - 1] + '</th>';
                htmlStringTotal += '<td class="total" id="total_month_short_' + ele_month + '">' + amountTotalValue + '</td>';
                htmlStringPaid += '<td class="paid" id="paid_month_short_' + ele_month + '">' + amountPaidValue + '</td>';
                htmlStringUnpaid += '<td class="unpaid" id="unpaid_month_short_' + ele_month + '">' + amountTUnpaidValue + '</td>';
                temp_amount = (data_total[ele_month] !== undefined) ? data_total[ele_month].replace('\'', '') : 0;
                amount = (isNaN(window.parseInt(temp_amount))) ? 0 : window.parseInt(temp_amount)
                totalData.push(amount);
                temp_amount = (data_paid[ele_month] !== undefined) ? data_paid[ele_month].replace('\'', ''): 0;
                amount = (isNaN(window.parseInt(temp_amount))) ? 0 : window.parseInt(temp_amount)
                paidData.push(amount);
                temp_amount = (data_unpaid[ele_month] !== undefined) ? data_unpaid[ele_month].replace('\'', '') : 0;
                amount = (isNaN(window.parseInt(temp_amount))) ? 0 : window.parseInt(temp_amount)
                unpaidData.push(amount);

            }
            $.each(totalData, function (i, t) {
                totalTotalValue += parseFloat(t);
            });
            $.each(paidData, function (i, t) {
                totalPaidValue += parseFloat(t);
            });
            $.each(unpaidData, function (i, t) {
                totalUnpaidValue += parseFloat(t);
            });
            htmlStringMonth += '<th>Total</th></tr></thead>';
            htmlStringTotal += '<td class="total">' + totalTotalValue.toLocaleString("de-CH", {minimumFractionDigits: 2}) + '</td></tr>';
            htmlStringPaid += '<td class="paid">' + totalPaidValue.toLocaleString("de-CH", {minimumFractionDigits: 2}) + '</td></tr>';
            htmlStringUnpaid += '<td class="unpaid">' + totalUnpaidValue.toLocaleString("de-CH", {minimumFractionDigits: 2}) + '</td></tr>';
            totalTotalValue = 0;
            totalPaidValue = 0;
            totalUnpaidValue = 0;

            $('#month_' + year).append(htmlStringMonth);
            $('#data-total-short_' + year).append(htmlStringTotal);
            $('#data-total-short_' + year).append(htmlStringPaid);
            $('#data-total-short_' + year).append(htmlStringUnpaid);
            graphSeries.push(
                {
                    name: 'Bezahlt',
                    data: paidData,
                    color: '#333'
                }
            );
            graphSeries.push(
                {
                    name: 'Unbezahlt',
                    data: unpaidData,
                    color: '#ff9900'
                }
            );
            $('#charts_' + year).append('<div id="chart_div_' + year + '"></div>')
            if (unpaidData.reduce(function (pv, cv) {
                    return pv + cv;
                }, 0) > 0 || paidData.reduce(function (pv, cv) {
                    return pv + cv;
                }, 0) > 0) {
                $(function () {
                    $('#chart_div_' + year).highcharts({
                        chart: {
                            type: 'column',
                            height: 400
                        },
                        title: {
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            },
                            text: 'Rechnungen ' + year
                        },
                        xAxis: {
                            categories: window.langCalendar,
                            labels: {
                                style: {
                                    fontSize: '18px'
                                }
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'CHF',
                                labels: {
                                    style: {
                                        fontSize: '18px'
                                    }
                                }
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    fontSize: '18px',
                                    color: (Highcharts.theme && Highcharts.theme.textColor) || $('.total').css('color')
                                },
                                formatter: function () {
                                    if (this.total > 0) {
                                        return this.total;
                                    }
                                }
                            }
                        },
                        legend: {
                            align: 'right',
                            x: -30,
                            verticalAlign: 'top',
                            y: 25,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                            borderColor: '#CCC',
                            borderWidth: 1,
                            shadow: false
                        },
                        tooltip: {
                            headerFormat: '<b>{point.x}</b><br/>',
                            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                        },
                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                dataLabels: {
                                    enabled: true,
                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                    style: {
                                        textShadow: '0 0 3px black',
                                        fontWeight: 'bold',
                                        fontSize: '18px'
                                    }
                                }
                            },
                            series: {
                                dataLabels: {
                                    enabled: true,
                                    formatter: function () {
                                        if (this.y > 0) {
                                            return this.y;
                                        }
                                    }
                                }
                            }
                        },
                        series: graphSeries
                    });
                    graphSeries = [];

                });
            } else {
                $('#chart_div_' + year).html('Keine Daten fürs Jahr ' + year);
                $('#month_' + year).hide();
                $('#total_' + year).hide();
                $('#paid_' + year).hide();
                $('#unpaid_' + year).hide();
            }
            totalData = [];
            paidData = [];
            unpaidData = [];
            if ($.fn.DataTable.isDataTable(ele)) {
                $(ele).destroy();
            }
            $(ele).DataTable(dataTableOptions);
        });
        window.getStatsData('/stats_bill_total', years, fillYearTotalTable);
        window.getStatsData('/stats_bill', years, fillMonthTable);
        window.checkedYear = [];
    };

$(document).ready(function () {
    'use strict';
    $('[name^="year"]').on('change', function (event, state) {
        window.checkedYear = [];
        window.showYear = [];
        $.each($('[name^="year"]'), function (i, n) {
            if ($(n).is(':checked')) {
                window.checkedYear.push(n.value + '-%');
                window.showYear.push(n.value);
            }
            window.yearColors[n.value] = window.yearColorsSet[i];
        });
        window.checkedYear.sort();
        window.showYear.sort();
    });
    $(document).on('click', '#getYears', function () {
        if (window.checkedYear.length === 0) {
            $('[name^="year"]').trigger('change');
        }
        window.getStatsData('/stats_bill_total_year', window.checkedYear, window.fillTable);
    });
})
