$(document).ready(function () {
    "use strict";
    CKEDITOR.replace( 'setting_bill_text', {
        config: {
            extraPlugins: 'uploadimage',
        }
    } );
    CKEDITOR.replace( 'setting_bill_mail_text', {
        config: {
            extraPlugins: 'uploadimage',
        }
    } );
    CKEDITOR.replace( 'setting_start_reservation_mail_text', {
        config: {
            extraPlugins: 'uploadimage',
        }
    } );

});
