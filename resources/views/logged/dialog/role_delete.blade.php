<div id="delete_role_from_user" role="dialog" aria-labelledby="delete_role_from_user" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
      <span id="roleToDelete" style="display: none"></span>
        <p>{!!trans('dialog.texts.warning_delete_role_from_user', array('user_name' => $user->user_first_name . ' ' . $user->user_name))!!}</p>
        <ul>
            <li id="roleToDeleteText"></li>
        </ul>
      </div>
      <div class="modal-footer">
      <div class="modal-footer-text">
      {!!trans('dialog.footer_texts.warning_delete_role_from_user')!!}
      </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
        <button id="confirmDeleteRole_{!!$user->id!!}" data-dismiss="modal" type="button" class="btn btn-primary">{!!trans('dialog.yes')!!}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->