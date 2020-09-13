<div id="notify_new_post" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-info">{!! trans('dialog.info') !!}</h4>
            </div>
            <div class="modal-body">
                <p>{!!trans('news.notify')!!}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">
                    {{ trans('dialog.ok') }}
                </button>
            </div>
        </div>
    </div>
</div>
