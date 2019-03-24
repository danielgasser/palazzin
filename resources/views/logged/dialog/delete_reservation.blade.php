<div id="delete_reservation" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p>{!!trans('dialog.texts.warning_delete_reservation')!!}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">{!!trans('dialog.no')!!}</button>
                <button id="deleteRes" class="btn btn-default btn-dialog-right">{!!trans('dialog.y')!!}</button>
            </div>
        </div>
    </div>
</div>
