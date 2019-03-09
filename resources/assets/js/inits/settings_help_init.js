/**
 * Created by pc-shooter on 17.12.14.
 */
$(document).ready(function () {
    "use strict";

    window.tinymce.init({
        selector: 'textarea',
        plugins : 'autoresize',
        autoresize_min_height: 400,
        autoresize_max_height: 800,
        language: 'de',
        height: '100%'
    });

    $('[id^="toop_"]').hide();
    $(document).on('change', '#getset', function () {
        $('[id^="toop_"]').hide();
        $('#toop_' + this.value).show();
    });
});
