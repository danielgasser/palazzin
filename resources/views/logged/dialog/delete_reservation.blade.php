<div id="delete_reservation" role="dialog" aria-labelledby="delete_reservation" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
        <p>{!!trans('dialog.texts.warning_delete_reservation')!!}</p>
      </div>
      <div class="modal-footer"><span id="guest-form-id" style="display: none"></span>
      <div class="modal-footer-text">
      {!!trans('dialog.footer_texts.warning_delete_reservation')!!}
      </div>
        <button id="cancel_delete_reservation" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
        <button id="deleteRes" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.yes')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
