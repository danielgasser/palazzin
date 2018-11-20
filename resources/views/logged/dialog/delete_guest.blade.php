<div id="delete_guest" role="dialog" aria-labelledby="no_free_beds" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
      <span id="noFree" style="display: none"></span>
        <p>{!!trans('dialog.texts.warning_delete_guest')!!}</p>
      </div>
      <div class="modal-footer"><span id="guest-form-id" style="display: none"></span>
      <div class="modal-footer-text">
        </div>
        <button id="confirm_delete_guest" data-id="" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.y')!!}</button>
        <button id="cancel_delete_guest" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.n')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
