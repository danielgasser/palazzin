<div id="global_setting_save" role="dialog" aria-labelledby="global_setting_save" aria-hidden="true" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{!!trans('dialog.warning')!!}</h4>
      </div>
      <div class="modal-body">
        <p>{!!trans('dialog.texts.warning_save_global_settings', array('site' => App::make('GlobalSettings')->getSettings()->setting_site_url))!!}</p>
      </div>
      <div class="modal-footer">
      <div class="modal-footer-text">
      {!!trans('dialog.footer_texts.warning_save_global_settings')!!}
      </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">{!!trans('dialog.no')!!}</button>
          {!!Form::submit(trans('dialog.yes'), array('id' => 'saveSettings', 'class' => 'btn btn-primary'))!!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->