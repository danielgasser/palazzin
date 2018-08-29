/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";

    window.tinymce.init({
        selector: 'textarea',
        plugins : 'autoresize',
        autoresize_min_height: 400,
        autoresize_max_height: 800,
        language: 'de',
        height: '100%'
    });

    jQuery('[id^="toop_"]').hide();
    jQuery(document).on('change', '#getset', function () {
        jQuery('[id^="toop_"]').hide();
        jQuery('#toop_' + this.value).show();
    });
});