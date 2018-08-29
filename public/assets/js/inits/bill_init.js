/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    $.each(jQuery('input'), function (i, n) {
        n.value = '';
    });
    $.datepicker.setDefaults({
        showOn: 'focus',
        buttonImageOnly: false,
        buttonText: '',
        showButtonPanel: false
    });
    // datepicker overall
    $.datepicker.setDefaults($.datepicker.regional[window.locale.split('-')[0]]);
    window.adaptEmptyInputs();
    jQuery(document).on('click', '[id^="savePaid_"]', function (e) {
        var idValue = $(this).attr('id').split('_')[2],
            id = $(this).attr('id').split('_')[1],
            dateVal = $('#bill_paid_' + idValue).val().split('.'),
            dbDate;
        // datepicker overall
        dbDate = dateVal[2] + '-' + dateVal[1] + '-' + dateVal[0];
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/admin/bills/paid',
            data: {
                id: id,
                bill_paid: dbDate
            },
            success: function (data) {
                $('#paid_or_not_' + idValue).html(window.pay_yesno[data.due]);
                $('#when_paid_' + idValue).html(data.paid + '<br><button class="btn btn-default" id="undoSavePaid_' + data.billid + '_' + idValue + '">' + window.langDialog.reset + '</button>');
                window.adaptEmptyInputs();
            }
        });
    });
    jQuery(document).on('click', '[id^="undoSavePaid_"]', function (e) {
        var id = $(this).attr('id').split('_')[1],
            idValue = $(this).attr('id').split('_')[2];
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/admin/bills/unpaid',
            data: {
                id: id
            },
            success: function (data) {
                $('#paid_or_not_' + idValue).html(window.pay_yesno[data.due]);
                $('#when_paid_' + idValue).html('<br><input class="form-control date_type" id="bill_paid_' + idValue + '" data_id="' + data.billid + '" name="bill_paid" type="text"><button class="btn btn-default" id="savePaid_' + data.billid + '_' + idValue + '">' + window.langDialog.save + '</button>');
                window.adaptEmptyInputs();
            }
        });
    });
    jQuery(document).on('click', '#keeperData>tr', function () {
        var s = $(this).children('[id^="currentCalDate_"]').attr('id').split('_'),
            d = new Date(Date.UTC(s[1], (parseInt(s[2], 10) - 1), parseInt(s[3], 10)));
        d = new Date(s[1], (parseInt(s[2], 10) - 1), parseInt(s[3], 10));
        window.setCurrentCalendarDate(d);
        window.location.href = '/reservation';
    });

});
