/**
 * Created by pc-shooter on 27.12.14.
 */

var GuestForm = '<fieldset class="new" id="guestFormID"><legend>' + window.langRes.guest_title + '<span></span></legend>' +
    '<div class="row">' +
    '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">' +
    '<label for="reservation_guest_started_at">' + window.langRes.arrival  + '</label>' +
    '<input class="form-control date_type" name="reservation_guest_started_at[]" type="text" id="reservation_guest_started_at" min="2015-01-04" max="2015-01-11" readonly="readonly">' +
    '<input name="reserv_guest_id[]" type="hidden" id="reserv_guest_id">' +
    '</div>' +
    '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">' +
    '<label for="reservation_guest_ended_at">' + window.langRes.depart  + '</label>' +
    '<input class="form-control date_type" name="reservation_guest_ended_at[]" type="text" id="reservation_guest_ended_at" min="2015-01-04" max="2015-01-11" readonly="readonly">' +
    '</div>' +
    '<div class="col-xs-6 col-sm-4 col-md-6 col-lg-3 for_others">' +
    '<label for="reservation_guest_guests">' + window.langRes.guest_kind  + '</label>' +
    '<select class="form-control" id="reservation_guest_guests" name="reservation_guest_guests[]">';
$.each(window.rolesTrans, function (v, t) {
    "use strict";
    GuestForm += '<option value="' + v + '">' + t + '</option>';
});
GuestForm +=
    '</select>' +
    '</div>' +
    '<div class="col-xs-6 col-sm-2 col-md-2 col-lg-1">' +
    '<label for="reservation_guest_num">' + window.langRes.guests.number + ' ' + window.langRes.guests.title  + '</label>' +
    '<span class="reservation_guest_num"><input class="form-control" min="1" max="15" name="reservation_guest_num[]" type="number" value="1" id="reservation_guest_num" onkeydown="return false"></span>' +
    '<input name="rg_num_old" type="hidden" value="1" id="rg_num_old">' +
    '</div>' +
    '<div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">' +
    '<label for="reservation_guest_role_tax_night">' + window.langRes.guests.tax_night  + '</label>' +
    '<input class="form-control" disabled="disabled" min="1" max="15" name="reservation_guest_role_tax_night[]" type="text" id="reservation_guest_role_tax_night" value="0.0">' +
    '<input class="form-control" name="reservation_guest_role_tax_night_real[]" type="hidden" id="reservation_guest_role_tax_night_real" value="0.0">' +
    '</div>' +
    '<div class="col-xs-6 col-sm-3 col-md-2 col-lg-1">' +
    '<label for="reservation_guest_nights_show">' + window.langRes.nights  + '</label>' +
    '<input class="form-control" disabled="disabled" name="reservation_guest_nights_show[]" type="text" id="reservation_guest_nights_show">' +
    '<input class="form-control" name="reservation_guest_nights[]" type="hidden" id="reservation_guest_nights">' +
    '</div>' +
    '</div>' +
        '<div class="row">' +
    '<div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">' +
    '<label for="reservation_guest_role_tax_night_show_total">' + window.langBill.total_bill  + '</label>' +
    '<input class="form-control" disabled="disabled" min="1" max="15" name="reservation_guest_role_tax_night_show_total[]" type="text" id="reservation_guest_role_tax_night_show_total" value="0.0">' +
    '<input name="reservation_guest_role_tax_night_total[]" type="hidden" id="reservation_guest_role_tax_night_total">' +
    '</div>' +
    '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">' +
    '<label for="reservation_guest_save_nights">' + window.langDialog.actions  + '</label>' +
    '<button class="btn btn-success" disabled="disabled" name="reservation_guest_save_nights[]" type="text" id="reservation_guest_save_nights">' +
    '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' +
    '</button>&nbsp;' +
    '<button id="deleteGuest" name="deleteGuest[]" class="btn btn-danger">' +
    '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
    '</button>' +
    '</div>' +
    '</div>' +
    '</fieldset>';
