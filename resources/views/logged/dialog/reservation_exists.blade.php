<div id="reservation_exists" class="alert alert-success alert-dismissible" style="display: none">{!! trans('reservation.warnings.cross_reserv')  !!}<hr>
    <form id="edit_reservation_exists" method="post" action="">
        {{ csrf_field() }}
        <button type="submit" id="edit_reservation_exists" class="btn btn-default">{!!trans('dialog.y')!!}</button>
    </form>
        <button id="cancel_reservation_exists" class="btn btn-default">{!!trans('dialog.no')!!}</button>
</div>
