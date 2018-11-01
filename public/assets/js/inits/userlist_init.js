/**
 * Created by pc-shooter on 17.12.14.
 */

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
