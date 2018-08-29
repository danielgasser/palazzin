<div id="cross_reserv_user_list" role="dialog" aria-labelledby="cross_reserv_user_list" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content panel-default">
      <div class="modal-body">
        <h4>{!!trans('reservation.warnings.cross_reserv_user_list')!!}</h4>
        <span id="guestFormClanOtherId" style="display: none;"></span>
        <ul id="userlist"></ul>
      </div>
      <div class="modal-footer"><span id="guest-form-id" style="display: none"></span>
      <div class="modal-footer-text">
        </div>
        <button id="cancel_delete_reservation" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
        <button id="choose-user" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.ok')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->