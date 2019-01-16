/**
 * Created by pcshooter on 05.10.14.
 */

var getData = function (url, params) {
    "use strict";
    $.ajax({
        type: 'POST',
        url: url,
        data: params,
        success: function (data) {
            window.unAuthorized(data);
            window.location.reload();
        }
    });
};
var postErrors = function (params) {
    "use strict";
    var err = {
        error: params[0],
        url: params[1],
        line: params[2],
        url_where: params[3]
    }
    $.ajax({
        type: 'POST',
        url: 'js-errors',
        data: err,
        async: false,
        success: function (data) {
            window.unAuthorized(data);
            window.console.log(data);
        }
    });
};
