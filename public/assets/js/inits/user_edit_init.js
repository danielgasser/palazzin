/**
 * Created by pc-shooter on 15.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    var z;
    if ($('#error-wrap').length > 0) {
        z = $('.error-field').first().text();
        $('.' + z).focus();
    }
    jQuery(document).on('click', '[id^="deleteRole_"]', function () {
        var uid = jQuery(this).attr('id').split('_')[1];
        jQuery('#roleToDeleteText').text(jQuery(this).next('td').text());
        jQuery('#roleToDelete').text(uid);
        jQuery('#delete_role_from_user').show();
    });

    jQuery(document).on('click', '[id^="confirmDeleteRole_"]', function () {
        var uid = jQuery(this).attr('id').split('_')[1],
            rid = jQuery('#roleToDelete').text();
        window.getData(window.user_delete, {role_id: rid, id: uid});
    });
    jQuery(document).on('click', '[id^="add_role"]', function (e) {
        e.preventDefault();
        var uid = jQuery('#role_id').val();
        window.getRoles(window.add_role, uid);
    });
    jQuery(document).on('change', '#clan_id', function () {
        if (window.route.indexOf('admin') > -1) {
            return false;
        }
        var clan = jQuery(this).val(),
            families = window.families;
        jQuery('#user_family').find('option').remove();
        jQuery.each(families[clan], function (i, n) {
            jQuery('#user_family').append(new window.Option(n, i));
        });
    });
    jQuery(document).on('click', '#activate', function (e) {
        e.preventDefault();
        window.activateUser(window.user_activate, jQuery('#user_active').val(), window.user_id);
    });
    jQuery(document).on('click', '#changeClan', function (e) {
        e.preventDefault();
        window.changeClan(window.change_clan, jQuery('#clan_id').val(), window.user_id);
    });

});
