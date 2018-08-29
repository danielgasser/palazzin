<div id="lower">
    {!!Form::model($reservation, array('url' => 'reservation/new', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'resForm'))!!}
    <div id="editReservMenu" class="row form-group">
        <div id="leg">
        <h4>{!!trans('reservation.new_res')!!}</h4>
            <h5 id="p_show"></h5>
            <h5 style="color:black; text-align: center; font-weight: bold" id="overSeaLabel"></h5>
        </div>
        <div id="parentResActions">
                    <fieldset>
                        <div class="row">
                            <legend>Gesamtdauer der Reservation (Gastgeber Aufenthalt)</legend>
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                {!!Form::label('reservation_started_at', trans('reservation.arrival'))!!}
                                {!!Form::input('text', 'reservation_started_at', /*\Carbon\Carbon::createFromFormat('Y_m_d', $reservation->reservation_started_at)->formatLocalized(trans('formats.input-date'))*/null, array('class' => 'form-control date_type', 'readonly' => 'readonly'))!!}
                                {!!Form::hidden('period_id', null, ['id' => 'period_id'])!!}
                                {!!Form::hidden('period_clan_id', null, ['id' => 'period_clan_id'])!!}
                                {!!Form::hidden('res_id', null, ['id' => 'res_id'])!!}
                                {!!Form::hidden('existentResId', null, ['id' => 'existentResId'])!!}
                                {!!Form::hidden('user_id', Auth::user()->id, ['id' => 'user_id'])!!}
                                {!!Form::hidden('userIdAb', 'xx', ['id' => 'userIdAb'])!!}
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                {!!Form::label('reservation_ended_at', trans('reservation.depart'))!!}
                                {!!Form::input('text', 'reservation_ended_at', /*\Carbon\Carbon::createFromFormat('Y_m_d', $reservation->reservation_ended_at)->formatLocalized(trans('formats.input-date'))*/null, array('class' => 'form-control date_type', 'readonly' => 'readonly'))!!}
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-2 col-lg-1">
                                {!!Form::label('reservation_nights_show', trans('reservation.nights'))!!}
                                {!!Form::text('reservation_nights_show', /*$reservation->reservation_nights*/null, array('class' => 'form-control', 'disabled'))!!}
                                {!!Form::hidden('reservation_nights', /*$reservation->reservation_nights*/null, array('class' => 'form-control', 'id' => 'reservation_nights'))!!}
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-2" style="position: relative;">
                                {!!Form::label('reservation_guest_sum_num', trans('reservation.guests.number') . ' ' . trans('reservation.guests.pers'))!!}
                                {!!Form::input('number', 'reservation_guest_sum_num', /*$reservation->reservation_nights*/null, array('class' => 'form-control', 'disabled', 'style' => 'width: 30%'))!!}
                                <span id="info_guest_num"><span id="info_guest_num_text_one">{!!trans('reservation.me_alone')!!}</span> <span id="info_guest_num_number"></span> <span id="info_guest_num_text_two">{!!trans('reservation.guests.title')!!}</span></span>
                                {!!Form::hidden('reservation_guest_sum_num_old', /*$reservation->reservation_nights*/null, array('id' => 'reservation_guest_sum_num_old'))!!}
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                                {!!Form::label('reservation_total_sum', trans('bill.total_bill') . ' CHF')!!}
                                {!!Form::input('number', 'reservation_total_sum', 0.0, array('class' => 'form-control', 'disabled'))!!}
                            </div>
                            <div class="col-xs-6 col-sm-2 col-md-2">
                                {!!Form::label('addGuest', trans('reservation.guest') . ' ' . trans('dialog.add_on'))!!}
                              <button id="addGuest" class="btn btn-success">{!!trans('reservation.guest')!!} {!!trans('dialog.add_on')!!}</button>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div id="guest-forms">
                 <div id="guestForm"></div>
                </div>
                <div id="resActions">
                 <fieldset>
                    <legend>{!!trans('profile.actions')!!}</legend>
                     <div class="col-xs-12 col-sm-4 col-md-4">
                         <a href="#cancelEditReserv" id="cancelEditReserv" class="btn btn-default hundertpro">{!!trans('dialog.no')!!}</a>
                     </div>
                     <div class="col-xs-12 col-sm-4 col-md-4">
                         <a href="#" id="deleteEditReserv" class="btn btn-default hundertpro">{!!trans('reservation.delete')!!}</a>
                     </div>
                     <div class="col-xs-12 col-sm-4 col-md-4">
                         {!!Form::submit(trans('reservation.book'), ['id' => 'saveEditReserv', 'class' => 'btn btn-success hundertpro', 'disabled'])!!}
                     </div>
                 </fieldset>
           </div>
        </div>
    </div>
{!!Form::close()!!}

</div>
