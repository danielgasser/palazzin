function clan() {
    var clan_id = $("#clan_id").val(),
        fam = (typeof clan_id == 'string' && clan_id === '0') ? window.families : window.families[clan_id],
        is_none = (typeof clan_id == 'string' && clan_id === '0');
    $("#family_code")
        .find("optgroup").remove();
    $("#family_code")
        .find("option").remove();
    if (!is_none) {
        $.each(fam, function(a, b) {
            $("#family_code").append(new window.Option(b, a))
        })
    } else {
        $.each(window.families, function(i, n) {
            $.each(n, function(a, b) {
                $("#family_code").append(new window.Option(b, a))
            })
        })
    }
    $("#family_code").val(window.family_code)
}

$(document).ready(function () {
    clan();

});
$(document).on('change', '#clan_id', function () {
    $('#family_code').attr('disabled', false)
    clan();

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
$(document).on('click', '#edit_add_role', function (e) {
    e.preventDefault();
    $.ajax({
        url: window.urlTo + '/admin/users/addrole',
        method: 'POST',
        data: {
            user_id: $('[name="id"]').val(),
            role_id: $('#role_id').val(),
        },
        success: function (data) {
            if ($.parseJSON(data) === 'false' || $.parseJSON(data).hasOwnProperty('error')) {
                return false;
            }
            let d = $.parseJSON(data);
            GlobalFunctions.fillUserRoles(d[0], true);
        }
    })
});
