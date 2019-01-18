/**
 * Created by daniel on 24.05.16.
 */
jQuery(document).ready(function() {
    "use strict";
    var a;
    $("#error-wrap").length > 0 && (a = $(".error-field").first().text(), $("." + a).focus()), jQuery(document).on("click", "#addRight", function(a) {
        a.preventDefault();
        var b = jQuery("#right_id").val(),
            c = jQuery('[name="id"]').val();
        window.rightFromRole(window.role_rights, b, c)
    }), jQuery(document).on("click", '[id^="confirmDeleteRight_"]', function() {
        var a = jQuery(this).attr("id").split("_")[1],
            b = jQuery("#rightToDelete").text();
        window.rightFromRole(window.role_delete, b, a)
    }), jQuery(document).on("click", '[id^="deleteRight_"]', function(a) {
        a.preventDefault(), jQuery("#rightToDelete").html($(this).attr("id").split("_")[1]), jQuery("#rightToDeleteText").html($(this).parent().next("td").text()), jQuery("#delete_right_from_role").modal({
            backdrop: "static",
            keyboard: !1
        })
    })
}), jQuery(document).ready(function() {
    "use strict";
    var a;
    $("#error-wrap").length > 0 && (a = $(".error-field").first().text(), $("." + a).focus()), jQuery(document).on("click", "#addRight", function(a) {
        a.preventDefault();
        var b = jQuery("#right_id").val(),
            c = jQuery('[name="id"]').val();
        window.rightFromRole(window.role_rights, b, c)
    }), jQuery(document).on("click", '[id^="confirmDeleteRight_"]', function() {
        var a = jQuery(this).attr("id").split("_")[1],
            b = jQuery("#rightToDelete").text();
        window.rightFromRole(window.role_right_delete, b, a)
    }), jQuery(document).on("click", '[id^="deleteRight_"]', function(a) {
        a.preventDefault(), jQuery("#rightToDelete").html($(this).attr("id").split("_")[1]), jQuery("#rightToDeleteText").html($(this).parent().next("td").text()), jQuery("#delete_right_from_role").modal({
            backdrop: "static",
            keyboard: !1
        })
    })
}), jQuery(document).ready(function() {
    "use strict";
    window.tinymce.init({
        selector: "textarea",
        plugins: "autoresize",
        autoresize_min_height: 400,
        autoresize_max_height: 800,
        language: "de",
        height: "100%"
    }), jQuery('[id^="toop_"]').hide(), jQuery(document).on("change", "#getset", function() {
        jQuery('[id^="toop_"]').hide(), jQuery("#toop_" + this.value).show()
    })
}), jQuery(document).ready(function() {
    "use strict";
    jQuery(document).on("click", "#saveIt", function(a) {
        jQuery("#global_setting_save").show();
        a.preventDefault()
    })
}), jQuery(document).ready(function() {
    "use strict";
    var a;
    $("#error-wrap").length > 0 && (a = $(".error-field").first().text(), $("." + a).focus()), jQuery(document).on("click", '[id^="deleteRole_"]', function() {
        var a = jQuery(this).attr("id").split("_")[1];
        jQuery("#roleToDeleteText").text(jQuery(this).next("td").text());
        jQuery("#roleToDelete").text(a);
        jQuery("#delete_role_from_user").show();
    });
    jQuery(document).on("click", '[id^="confirmDeleteRole_"]', function() {
        var a = jQuery(this).attr("id").split("_")[1],
            b = jQuery("#roleToDelete").text();
        window.getData(window.user_delete, {
            role_id: b,
            id: a
        })
    }), jQuery(document).on("click", '[id^="add_role"]', function(a) {
        a.preventDefault();
        var b = jQuery("#role_id").val();
        window.getRoles(window.add_role, b)
    }), jQuery(document).on("change", "#clan_id", function() {
        if (window.route.indexOf("admin") === -1) return !1;
        var a = jQuery(this).val(),
            b = window.families;
        jQuery("#user_family").find("option").remove();
        jQuery("#user_family").append(new window.Option('Bitte Halbstamm wählen', '0'));
        jQuery.each(b[a], function(a, b) {
            jQuery("#user_family").append(new window.Option(b, a))
        })
    }), jQuery(document).on("click", "#activate", function(a) {
        a.preventDefault(), window.activateUser(window.user_activate, jQuery("#user_active").val(), window.user_id)
    }), jQuery(document).on("click", "#changeClan", function(a) {
        a.preventDefault(), window.changeClan(window.change_clan, jQuery("#clan_id").val(), window.user_id)
    })
});
