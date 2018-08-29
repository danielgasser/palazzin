/**
 * Created by pc-shooter on 17.12.14.
 */
jQuery(document).ready(function () {
    "use strict";
    if (window.route === 'admin/users') {
        jQuery('#users').tablesorter({
            cssAsc: 'headerSortUp',
            cssDesc: 'headerSortDown',
            headers: {
                0: {
                    sorter: false
                },
                1: {
                    sorter: false
                },
                2: {
                    sorter: false
                },
                3: {
                    sorter: false
                }
            }
        });
    } else {
        jQuery('#users').tablesorter({
            cssAsc: 'headerSortUp',
            cssDesc: 'headerSortDown',
            headers: {
                0: {
                    sorter: false
                },
                1: {
                    sorter: false
                },
                11: {
                    sorter: false
                },
                14: {
                    sorter: false
                }
            }
        });
    }
    //$('#users').TableWizard();
});
    $(document).on('click', '[id^="destroyUser_"]', function (e) {
        e.preventDefault();
        var user_name = $(this).attr('id').split('_');
        $('#modal-username').text(user_name[2] + ' ' + user_name[3]);
        $('#confirmDeleteUser').attr('href', user_name[1]);
        $('#delete_user').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $(document).on('click', '#confirmDeleteUser', function () {
        window.location.href = window.urlTo + '/admin/users/delete/' + $(this).attr('href');
    });
