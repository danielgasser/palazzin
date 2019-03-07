<div id="session_expired" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p>{!!trans('dialog.texts.warning_login_again')!!}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="loginAgain" class="btn btn-default btn-dialog-right" data-dismiss="modal">
                    {{ trans('login.login') }}
                </button>
            </div>
        </div>
    </div>
</div>
