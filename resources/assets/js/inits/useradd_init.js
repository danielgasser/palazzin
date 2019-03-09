$(document).ready(function () {
    var clan_id = $("#clan_id").val(),
        fam = (typeof clan_id == 'string' && clan_id === '0') ? window.families : window.families[clan_id],
        is_none = (typeof clan_id == 'string' && clan_id === '0');
    $("#user_family").find("option").remove();
    $("#user_family").append(new window.Option('Bitte Halbstamm wählen', '0'));
    if (!is_none) {
        $.each(fam, function(a, b) {
            $("#user_family").append(new window.Option(b, a))
        })
    } else {
        $.each(window.families, function(i, n) {
            $.each(n, function(a, b) {
                $("#user_family").append(new window.Option(b, a))
            })
        })
    }
    if (window.addedRoles !== null) {
        $.each(window.addedRoles, function () {
            GlobalFunctions.fillUserRoles(this, true);
        })
    }
});
$(document).on('change', '#clan_id', function () {
    $('#user_family').attr('disabled', false)
})
