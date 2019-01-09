<div id="guest_empty" role="dialog" aria-labelledby="guest_empty" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{{trans('dialog.warning')}}</h4>
      </div>
      <div class="modal-body">
        <p>{{trans('reservation.warnings.no_guest_kind')}}</p>
      </div>
      <div class="modal-footer"><span id="guest-form-id" style="display: none"></span>
      <div class="modal-footer-text">

        </div>
        <button id="cancel_no_free_beds" type="button" class="btn btn-default" data-dismiss="modal">{{trans('dialog.ok')}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
