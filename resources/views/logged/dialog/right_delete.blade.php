<div id="delete_right_from_role" class="alert alert-danger alert-dismissible" style="display: none"><span
        id="rightToDelete" style="display: none"></span>
    <span>{{trans('dialog.texts.warning_delete_right_from_role', array('right_name' => trans('roles.' . $role->role_code)))}}</span>
    <ul>
        <li id="rightToDeleteText"></li>
    </ul>
    <button class="btn btn-default closeAlert">{!!trans('dialog.no')!!}</button>
    <button id="confirmDeleteRight_{{$role->id}}" class="btn btn-primary">{!!trans('dialog.yes')!!}</button>
</div>
