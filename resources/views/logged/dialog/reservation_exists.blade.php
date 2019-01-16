<div id="reservation_exists" role="dialog" aria-labelledby="reservation_exists" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
        <p id="message">{!! trans('reservation.warnings.cross_reserv')  !!}</p>
      </div>
      <div class="modal-footer">
      <div class="modal-footer-text">
        <a id="edit_reservation_exists" class="btn btn-default" href="{{ route('edit_reservation') }}">{!!trans('dialog.edit')!!}</a>
        <button id="cancel_reservation_exists" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
