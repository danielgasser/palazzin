<div id="delete_role_from_user" class="alert alert-danger alert-dismissible" style="display: none"><span
        id="roleToDelete" style="display: none"></span>
    <span>{{trans('dialog.texts.warning_delete_role_from_user', array('user_name' => $user->user_first_name . ' ' . $user->user_name))}}</span>
    <ul>
        <li id="roleToDeleteText"></li>
    </ul>
    <button class="btn btn-default closeAlert">{!!trans('dialog.no')!!}</button>
    <button id="confirmDeleteRight_{{$user->id}}" class="btn btn-primary">{!!trans('dialog.yes')!!}</button>
</div>
