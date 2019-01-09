<div id="four_char" role="dialog" aria-labelledby="four_char" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{{trans('dialog.warning')}}</h4>
      </div>
      <div class="modal-body">
      <span id="roleToDelete" style="display: none"></span>
        <p>{{trans('dialog.texts.warning_four_chars')}}</p>
      </div>
      <div class="modal-footer">
      <div class="modal-footer-text">
      </div>
        <button id="oho" data-dismiss="modal" type="button" class="btn btn-default">{{trans('dialog.ok')}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
