$(document).ready(function () {
    "use strict";
    var route,
        rs,
        getData = function (val) {
            var text = '';
            $.ajax({
                type: 'POST',
                url: '/help',
                data: {
                    help_topic: val
                },
                success: function (d) {
                    GlobalFunctions.unAuthorized(d);
                    if (d.hasOwnProperty('error_message')) {
                        text = d.error_message;
                    } else {
                        text = d.help_text;
                    }
                    $('#topic_text').html(text);
                    $('[name="help_topic"]').val(val);
                }
            });
        };
    route = document.URL.split('/');


    if (route[route.length - 1].length > 0) {
        route.splice(0, 4);
        rs = route.join('_');
        $('[name="help_topic"]').val(rs);
    }

    $(document).on('change', '[name="help_topic"]', function () {
        getData($(this).val());
    });
});
