<div id="delete_right_from_role" role="dialog" aria-labelledby="delete_right_from_role" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
      <span id="rightToDelete" style="display: none"></span>
        <p>{{trans('dialog.texts.warning_delete_right_from_role', array('right_name' => trans('roles.' . $role->role_code)))}}</p>
        <ul>
            <li id="rightToDeleteText"></li>
        </ul>
      </div>
      <div class="modal-footer">
      <div class="modal-footer-text">
      {!!trans('dialog.footer_texts.warning_delete_role_from_user')!!}
      </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
        <button id="confirmDeleteRight_{{$role->id}}" data-dismiss="modal" type="button" class="btn btn-primary">{!!trans('dialog.yes')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
