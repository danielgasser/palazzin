<div id="delete_role_from_user" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p><span
        id="roleToDelete" style="display: none"></span>
    <span>{{trans('dialog.texts.warning_delete_role_from_user', array('user_name' => $user->user_first_name . ' ' . $user->user_name))}}</span>
                </p>
                <ul>
        <li id="roleToDeleteText"></li>
    </ul>
            </div>
            <div class="modal-footer">
                <button id="confirmDeleteRight_{{$role->id}}" class="btn btn-default btn-dialog-left">{!!trans('dialog.yes')!!}</button>
                <button class="btn btn-default btn-dialog-right">{!!trans('dialog.no')!!}</button>
            </div>
        </div>
    </div>
</div>
