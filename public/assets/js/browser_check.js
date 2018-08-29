/**
 * Created by pc-shooter on 17.12.14.
 */
navigator.sayswho = (function () {
    "use strict";
    var ua = navigator.userAgent, tem,
        M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if (/trident/i.test(M[1])) {
        tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE ' + (tem[1] || '');
    }
    if (M[1] === 'Chrome') {
        tem = ua.match(/\bOPR\/(\d+)/);
        if (tem !== null) {
            return 'Opera ' + tem[1];
        }
    }
    M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
    if ((tem = ua.match(/version\/(\d+)/i)) !== null) {
        M.splice(1, 1, tem[1]);
    }
    return M.join(' ');
})();
jQuery(document).ready(function () {
    "use strict";
    var browserCheck = false,
        exp = new Date();
    var mod = 'modernizr';
    window.Modernizr.addTest('localstorage', function () {
        try {
            window.localStorage.setItem(mod, mod);
            window.localStorage.removeItem(mod);
            browserCheck = true;
        } catch (e) {
            browserCheck = false;
        }
    });
  //  window.localStorage.setItem(mod, mod);

    if (navigator.sayswho.split(' ')[0] === 'IE') {
        if (parseInt(navigator.sayswho.split(' ')[1], 10) < 11) {
            browserCheck = true;
        }
    }
    if (navigator.sayswho.split(' ')[0] === 'Safari') {
        if (parseInt(navigator.sayswho.split(' ')[1], 10) < 7) {
            browserCheck = true;
        }
    }
    if (navigator.sayswho.split(' ')[0] === 'Firefox') {
        if (parseInt(navigator.sayswho.split(' ')[1], 10) < 31) {
            browserCheck = true;
        }
    }
    if (browserCheck) {
        jQuery('#canvasCheck').html(window.errors_modernizr);
        jQuery('#noview').hide();
    }
    exp.setTime(exp.getTime() + 3 * 60 * 1000);
    document.cookie = 'enabled=yes; expires=' + exp.toGMTString();
    if (document.cookie.indexOf('enabled') < 0) {
        jQuery('#testCookie').html(window.errors_cookies);
        jQuery('#noview').hide();
    }
});
