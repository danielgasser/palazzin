<?php
/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 15/11/2018
 * Time: 15:26
 */
?>
<div class="row" id="guests_date_{{ $i }}">
    <div class="col-md-12 col-sm-12 col-xs-12 no-hide" id="hider_{{ $i }}">
        <span id="hide_guest_{{ $i }}" class="fas fa-caret-up"></span>&nbsp;<span id="guest_title_{{ $i }}">{{trans('reservation.guest_many_no_js.one')}}: </span>
        <button title="{{trans('dialog.delete')}}" class="btn btn-danger btn-v3 show_reservation_guest"
                id="remove_guest_{{ $i }}"><i class="fas fa-trash-alt"></i></button>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <label>{{trans('reservation.arrival_departure')}}</label>
        <div class="input-daterange input-group" id="guestDates_{{ $i }}">
            <input type="text" id="reservation_guest_started_at_{{ $i }}" name="reservation_guest_started_at[]" class="input-sm form-control show_reservation{{ $errors->has('reservation_guest_started_at.' . $i) ? ' input-error' : ''}}"
                   placeholder="{{trans('reservation.arrival')}}" readonly value="{{ old('reservation_guest_started_at.' . $i) }}"/>
            <span class="input-group-addon">bis</span>
            <input type="text" id="reservation_guest_ended_at_{{ $i }}" name="reservation_guest_ended_at[]" class="input-sm form-control show_reservation{{ $errors->has('reservation_guest_ended_at.' . $i) ? ' input-error' : ''}}"
                   placeholder="{{trans('reservation.depart')}}" readonly value="{{ old('reservation_guest_ended_at.' . $i) }}"/>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 col-xs-6">
        <div class="form-group">
            <label>{{trans('reservation.guest_kind')}}</label>
            <select class="form-control show_reservation_guest{{ $errors->has('reservation_guest_guests.' . $i) ? ' input-error' : ''}}" id="reservation_guest_guests_{{ $i }}"
                    name="reservation_guest_guests[]">
                @foreach($rolesTrans as $k => $r)
                    <option {{ (old('reservation_guest_guests.' . $i) == $k) ? ' selected' : '' }} value="{{$k}}">{{$r}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 col-xs-6">
        <div class="form-group">
            <label>{{trans('reservation.guests.number')}} {{trans('reservation.guests.title')}}</label>
            <input class="form-control show_reservation_guest{{ $errors->has('reservation_guest_num.' . $i) ? ' input-error' : ''}}" id="reservation_guest_num_{{ $i }}"
                   name="reservation_guest_num[]" data-toggle="tooltip" data-html="true" title="{{trans('dialog.texts.warning_no_free_beds')}}" type="number" min="1"
                   max="{{$settings['setting_num_bed'] - 1}}" value="{{ old('reservation_guest_num.' . $i) }}">
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 no-hide">
        <div class="form-group">
            <div class="alert alert-info" id="total_guest_{{ $i }}">
                CHF <span id="reservation_guest_price_{{ $i }}">0</span>/{{trans('roles.tax_only')}}&nbsp;
                <span id="number_nights_{{ $i }}">0</span> {{trans('reservation.nights')}}
                CHF <span id="price_{{ $i }}">0.-</span>
                <input type="hidden" name="number_nights[]" id="hidden_number_nights_{{ $i }}" value="{{ old('number_nights.' . $i) }}">
                <input type="hidden" name="price[]" id="hidden_price_{{ $i }}" value="{{ old('price.' . $i) }}">
                <input type="hidden" name="hidden_reservation_guest_price[]" id="hidden_reservation_guest_price_{{ $i }}" value="{{ old('reservation_guest_price.' . $i) }}">
            </div>
        </div>
    </div>
</div>

