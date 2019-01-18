/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    var z;
    if ($('#error-wrap').length > 0) {
        z = $('.error-field').first().text();
        $('.' + z).focus();
    }

    jQuery(document).on('click', '#addRight', function (e) {
        e.preventDefault();
        var rightid = jQuery('#right_id').val(),
            roleid = jQuery('[name="id"]').val();
        window.rightFromRole(window.role_rights, rightid, roleid);
    });

    jQuery(document).on('click', '[id^="confirmDeleteRight_"]', function () {
        var roleid = jQuery(this).attr('id').split('_')[1],
            rightid = jQuery('#rightToDelete').text();
        window.rightFromRole(window.role_delete, rightid, roleid);
    });

    jQuery(document).on('click', '[id^="deleteRight_"]', function (e) {
        e.preventDefault();
        jQuery('#rightToDelete').html($(this).attr('id').split('_')[1]);
        jQuery('#rightToDeleteText').html($(this).parent().next('td').text());
        jQuery('#delete_right_from_role').show();
    });
});
