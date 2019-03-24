<div id="delete_right_from_role" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p><span
        id="rightToDelete" style="display: none"></span>
    <span>{{trans('dialog.texts.warning_delete_right_from_role', array('right_name' => trans('roles.' . $role->role_code)))}}</span>
                </p>
                <ul>
        <li id="rightToDeleteText"></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left">{!!trans('dialog.no')!!}</button>
                <button id="confirmDeleteRight_{{$role->id}}" class="btn btn-default btn-dialog-right">{!!trans('dialog.yes')!!}</button>
            </div>
        </div>
    </div>
</div>
