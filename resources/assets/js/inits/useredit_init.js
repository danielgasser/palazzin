$(document).ready(function () {
    if (window.addedRoles !== null) {
        $.each(window.addedRoles, function () {
            GlobalFunctions.fillUserRoles(this, true);
        })
    }
});
$(document).on('change', '#clan_id', function () {
    $('#user_family').attr('disabled', false)
});
$(document).on('change', '#user_active', function () {
    let user_active = $(this).val();
    $.ajax({
        url: window.urlTo + '/admin/users/activate',
        method: 'POST',
        data: {
            user_id: $('[name="id"]').val(),
            user_active: user_active,
        },
        success: function (data) {
        }
    })
});
$(document).on('click', '[id^="deleteRole_"]', function () {
    let t = $(this).attr('id').split('_'),
        user_id = t[2],
        role_id = t[1];
    $.ajax({
        url: window.urlTo + '/admin/users/edit/delete',
        method: 'POST',
        data: {
            user_id: user_id,
            role_id: role_id,
        },
        success: function (data) {
            $('#deleteRole_' + role_id + '_' + user_id).parent().remove();
        }
    })
});
