/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    jQuery(document).on('click', '#saveIt', function (e) {
        jQuery('#global_setting_save').modal({
            backdrop: 'static',
            keyboard: false
        });
        e.preventDefault();
    });
});