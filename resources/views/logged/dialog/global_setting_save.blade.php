<div id="global_setting_save" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title-info">{!! trans('dialog.info') !!}</h4>
            </div>
            <div class="modal-body">
                <p>{{trans('dialog.texts.warning_save_global_settings', array('site' => Setting::getStaticSettings()->setting_site_url))}}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left">{!!trans('dialog.no')!!}</button>
                {{Form::submit(trans('dialog.yes'), array('id' => 'saveSettings', 'class' => 'btn btn-default btn-dialog-right'))}}
            </div>
        </div>
    </div>
</div>
