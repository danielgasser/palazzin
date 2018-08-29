var setNavActive = function (el) {
    "use strict";
    var obj = $(el),
        url = window.location.pathname.split('/'),
        route = url.join('/');
    obj.removeClass('active');
    if (route === '/') {
        $('a[href="/"]').parent().addClass('active');
    }
    $('a[href$="' + route + '"]').parent().addClass('active');
};