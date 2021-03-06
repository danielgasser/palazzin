/**
 * Created by pc-shooter on 17.12.14.
 */
var p = [/ü/gi, /ä/gi, /ö/gi, /ò/gi, /é/gi, /è/gi, /à/gi, '-'],
    replaceWith = ['ue', 'ae', 'oe', 'o', 'e', 'e', 'a', '-'];

$(document).ready(function () {
    if (window.location.hash) {
        window.localStorage.setItem('news_hash', window.location.hash)
    }
    $('[data-toggle="popover"]').popover({
        html: true
    });
    if (window.oldie === '1') {
        $('#old_ie').show();
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ajaxStart(function () {
        $('#loading').show();
        $('.loading-day').show();
    });
    $(document).ajaxStop(function () {
        $('#loading').hide();
        $('.loading-day').hide();
    });
    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        $('#session_expired').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    setInterval(function () {
        $.ajax({
            type: 'GET',
            url: window.urlTo + '/check-session',
            success: function (data) {
            }
        })
    }, 1200000);
});
$(document).on('click', '#loginAgain', function () {
    location.href = "/login";
});
jQuery(document).on('click', '#errors>.close', function () {
    $('#error-wrap').hide();
});
jQuery(document).on('click', '.closeAlert', function () {
    $('.alert').hide();
});
jQuery(document).on('click', '[aria-label="Close"]', function () {
    $('.modal-dialog').modal('hide');
    $('.modal').removeClass('in');
});
jQuery(document).on('click', 'body', function () {
    $('.alert-success').hide();
});
jQuery(document).on('click', '.dropdownToggleUp', function (e) {
    let classes = e.target.classList;
    if (classes.contains('hide-footer-nav-text')) {
        $(this)
            .removeClass('dropdown-toggle-up')
            .toggleClass('dropdown-toggle-down');
    } else {
        $(this)
            .removeClass('dropdown-toggle-down')
            .toggleClass('dropdown-toggle-up');
        if (!$('#all-nav').hasClass('all-nav-hover')) {
            $('#all-nav').addClass('all-nav-hover');
        }
    }
});
jQuery(document).on('click', '#all-nav', function (e) {
    if (e.target !== this) {
        if ($(e.target).parent().parent('ul') !== undefined) {
            $('.dropdownToggleUp')
                .removeClass('dropdown-toggle-up')
                .toggleClass('dropdown-toggle-down');
        }
        return;
    }
    $('#closeNav').trigger('click');
});
jQuery(document).on('click', 'body', function (e) {
    if (e.target.id === 'closeNav' || e.target.id == 'all-nav') {
      return false;
    }
    $('.dropdownToggleUp')
        .removeClass('dropdown-toggle-up')
        .toggleClass('dropdown-toggle-down');
    $('#all-nav').removeClass('all-nav-hover');
});
jQuery(document).on('click', '#closeNav', function () {
    let nav = $('#all-nav');
    if (nav.hasClass('all-nav-hover')) {
        nav.removeClass('all-nav-hover');
        $('.dropdownToggleUp')
            .removeClass('dropdown-toggle-up')
            .toggleClass('dropdown-toggle-down');
    } else {
        nav.addClass('all-nav-hover');
    }
});
jQuery(document).on('click', '#toggleFooterNav', function () {
    $('#bottom-nav').slideToggle(500);
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
    $('#user_login_name_show').val(endLogin);
    $('#user_login_name').val(endLogin);
    jQuery(this).val(valStr);
});

