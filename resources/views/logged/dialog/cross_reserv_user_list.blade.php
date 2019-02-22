<div id="cross_reserv_user_list" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p>
                    <span id="guestFormClanOtherId" style="display: none;"></span>
                    <ul id="userlist"></ul>
                </p>
            </div>
            <div class="modal-footer">
                <button id="choose-user" class="btn btn-default btn-dialog-left">{!!trans('dialog.ok')!!}</button>
                <button type="button" class="btn btn-default btn-dialog-right" data-dismiss="modal">
                    {{ trans('dialog.no') }}
                </button>
            </div>
        </div>
    </div>
</div>
