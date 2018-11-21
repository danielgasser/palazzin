let V3DatePicker = {
    addClasses: function (picker) {
        console.log(window.periods, window.fullMonthNames)
        $.each($('td.day'), function () {
            let calWeek = parseInt($(this).parent('tr').find('td:first-child').html(), 10),
                monthYear = $('.table-condensed>thead>tr>th.datepicker-switch').html().split(' '),
                day = $(this).html(),
                month = $.inArray(monthYear[0], window.fullMonthNames) + 1,
                year = monthYear[1];
            calWeek = (calWeek < 10) ? '0' + calWeek : calWeek;
            month = (month < 10) ? '0' + month : month;
            day = (day < 10) ? '0' + day : day;
            console.log(day, month, year, window.datePickerPeriods['week_' + year + '_' + month + '_' + day + '_' + calWeek])
            if (!$(this).hasClass('disabled')) {
                $(this).addClass(window.datePickerPeriods['week_' + year + '_' + month  + '_' + day  + '_' + calWeek] + '-border')
            }
        });
    }
}
