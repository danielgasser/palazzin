<div id="global_setting_save" class="alert alert-danger alert-dismissible" style="display: none">
    {{trans('dialog.texts.warning_save_global_settings', array('site' => Setting::getStaticSettings()->setting_site_url))}}
    <hr>
    <button class="btn btn-default closeAlert">{!!trans('dialog.no')!!}</button>
    {{Form::submit(trans('dialog.yes'), array('id' => 'saveSettings', 'class' => 'btn btn-primary'))}}
</div>
