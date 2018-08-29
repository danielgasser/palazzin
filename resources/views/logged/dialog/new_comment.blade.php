<div id="new_comment_available" role="dialog" aria-labelledby="new_comment_available" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-info">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{!!trans('dialog.info')!!}</h4>
      </div>
      <div class="modal-body">
        <p id="message">{!!trans('comments.new_comment_available')!!}</p>
      </div>
      <div class="modal-footer"><span id="guest-form-id" style="display: none"></span>
      <div class="modal-footer-text">

        </div>
        <button id="cancel_new_comment" type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.ok')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->