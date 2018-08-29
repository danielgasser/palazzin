/**
 * Created by Daenu on 17.02.2015.
 */
(function ($) {
    "use strict";
    $.fn.TableWizard = function (options) {
        var settings = $.extend({
                tableHeaderSelector: 'th',
                tableBodySelector: '.tr-body>td',
                tableWidth: '100%',
                subTableWidth: '100%',
                isAjax: false
            }, options),
            init = function () {
                $('.table-responsive').css({
                    overflowX: 'auto',
                    overflowY: 'auto',
                    width: '100%'
                });
                $('#keeperDataHead').css({
                    width: settings.tableWidth
                });
                if (!settings.isAjax) {
                    $.each($(settings.tableHeaderSelector), function (i, n) {
                        var body_td = $('.tr-body>td:nth-child(' + (i + 1) + ')');
                        $(n).css({
                            width: body_td.outerWidth(),
                            minWidth: body_td.outerWidth(),
                            padding: '4px'
                        });
                    });
                } else {
                    $.each($(settings.tableBodySelector), function (i, n) {
                        var body_td = $('.table-head>tr>th:nth-child(' + (i + 1) + ')');
                        $(n).css({
                            width: body_td.outerWidth(),
                            minWidth: body_td.outerWidth(),
                            padding: '4px'
                        });
                    });
                }
            };
        $(window).bind('resize', function () {
            var res = window.resizeEvt;
            $(window).resize(function () {
                clearTimeout(res);
                window.resizeEvt = setTimeout(function () {
                    return init();
                }, 500);
            });
        });
        $('div').scroll(function () {
            $('thead.table-head>tr').css('top', ($(this).scrollTop() - 10) + 'px');
        });

        return init();
    };

}(jQuery));
