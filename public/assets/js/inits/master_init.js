/**
 * Created by pc-shooter on 17.12.14.
 */

jQuery(document).ready(function () {
    "use strict";
    var displayTexts = jQuery('#display-tests'),
        footer = jQuery('#footer'),
        p = [/ü/gi, /ä/gi, /ö/gi, /ò/gi, /é/gi, /è/gi, /à/gi, '-'],
        testBtn,
        replaceWith = ['ue', 'ae', 'oe', 'o', 'e', 'e', 'a', '-'];
    displayTexts.hide();
    $.datepicker.setDefaults($.datepicker.regional['']);

    $('[data-toggle="popover"]').popover({
        html: true
    });
    $(document).ajaxStart(function () {
        $('#loading').show();
        $('.loading-day').show();
    });
    $(document).ajaxStop(function () {
        $('#loading').hide();
        $('.loading-day').hide();
    });

    jQuery(document).on('click', '#open-admin > a', function () {
        window.location.href = this.href;
    });

    jQuery(document).on('click', '#show-tests', function () {
        displayTexts.toggle();
        footer.toggle();
        testBtn = (displayTexts.is(':hidden')) ? 'show' : 'hide';
        jQuery(this).text(testBtn);
    });

    jQuery(document).on('keyup', '#user_first_name, #user_name', function (e) {
        var strstr = jQuery(this).val().replace(/([^a-zA-ZÖÄÜöäüò\- .]+)/gi, ''),
            firstUp,
            valStr = '',
            endLogin,
            strArr,
            t = [];
        strArr = strstr.split('');
        firstUp = (strArr.length > 0) ? strArr[0] : '';
        strArr.splice(0, 1, firstUp.toUpperCase());
        jQuery.each(strArr, function (i, n) {
            valStr += n;
        });
        if (e.currentTarget.id === 'user_first_name') {
            t[0] = jQuery(this).val();
            t[1] = $('#user_name').val();
        } else {
            t[0] = $('#user_first_name').val();
            t[1] = jQuery(this).val();
        }
        endLogin = t.join('.').toLowerCase();
        jQuery.each(p, function (i, n) {
            endLogin = endLogin.replace(n, replaceWith[i]);
        });
        endLogin = endLogin.replace(/([^a-z.]+)/gi, '');
        $('#user_login_name, #user_login_name_show').val(endLogin);
        jQuery(this).val(valStr);
    });
    jQuery(document).on('click', '#post_new_post', function (e) {
        e.preventDefault();
    });

});
