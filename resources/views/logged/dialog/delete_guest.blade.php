<div id="delete_guest" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p>{!!trans('dialog.texts.warning_delete_guest')!!}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">
                    {{ trans('dialog.n') }}
                </button>
                <button class="btn btn-default btn-dialog-right" id="confirm_delete_guest">
                  {{ trans('dialog.y') }}
              </button>
            </div>
        </div>
    </div>
</div>
