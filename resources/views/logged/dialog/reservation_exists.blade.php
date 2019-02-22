<div id="reservation_exists" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p>
                {!! trans('reservation.warnings.cross_reserv')  !!}
                </p>
            </div>
            <div class="modal-footer">
                <form id="edit_reservation_exists" method="post" action="">
                    {{ csrf_field() }}
                    <button type="submit" id="edit_reservation_exists" class="btn btn-default btn-dialog-left">{!!trans('dialog.edit')!!}</button>
                </form>
                <button id="clearReservation" type="button" class="btn btn-default btn-dialog-right" data-dismiss="modal">
                {{ trans('dialog.n') }}
                </button>
            </div>
        </div>
    </div>
</div>
