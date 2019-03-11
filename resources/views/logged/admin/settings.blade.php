@extends('layout.master')
    @section('content')
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
<a href="{{URL::to('admin/settings/help')}}">{{trans('settings.title_help')}}</a>
    {{Form::model($setting, array('url' => 'admin/settings', 'class' => 'form-inline', 'id' => 'save-settings-form', 'files' => true))}}
    {{Form::hidden('id', $globalSettings->id)}}
{{ csrf_field() }}
        </div>
        </div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <h3>{{trans('settings.calendar')}}:</h3>
    </div>
    {{-- CalendarStart --}}
    {{Form::label('setting_calendar_start', trans('settings.CalendarStart'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-9 col-md-6">
        {{Form::input('text', 'setting_calendar_start', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $globalSettings->setting_calendar_start)->formatLocalized('%Y-%m-%d'), array('class' => 'form-control datepicker date_type', 'data-provide' => 'datepicker', 'placeholder' => 'YYYY-MM-DD'))}}
    </div>
</div>
<div class="row">
{{-- CalendarDuration --}}
    {{Form::label('setting_calendar_duration', trans('settings.CalendarDuration'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-3 col-md-3">
        {{Form::input('number', 'setting_calendar_duration', $globalSettings->setting_calendar_duration, array('class' => 'form-control', 'style' => 'width: 56px'))}}
    </div>
     {{-- CalendarStartingClan --}}
     {{Form::label('setting_num_bed', trans('settings.setting_num_bed'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-3 col-md-3">
        {{Form::input('number', 'setting_num_bed', $globalSettings->setting_num_bed, array('class' => 'form-control', 'style' => 'width: 66px'))}}
    </div>
 </div>
 <div class="row">
     {{Form::label('setting_starting_clan', trans('settings.CalendarStartingClan'), array('class' => 'col-sm-3 col-md-6'))}}
   <div class="col-sm-2 col-md-2">
        {{Form::select('setting_starting_clan', $clans, $globalSettings->setting_starting_clan, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
        {{Form::label('setting_reminder_days', trans('settings.setting_reminder_days'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {{Form::text('setting_reminder_days', $globalSettings->setting_reminder_days, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
        {{Form::label('setting_num_counter_clan_days', trans('settings.setting_num_counter_clan_days'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {{Form::text('setting_num_counter_clan_days', $globalSettings->setting_num_counter_clan_days, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    {{Form::label('', trans('settings.setting_num_counter_days_on_off', array('days' => $globalSettings->setting_num_counter_clan_days)), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {{Form::select('setting_num_counter_days_on_off', array('0' => trans('dialog.dont'), '1' => trans('dialog.do')) , $globalSettings->setting_num_counter_days_on_off, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    {{Form::label('', trans('settings.setting_counter_clan_on_off'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {{Form::select('setting_counter_clan_on_off', array('0' => trans('dialog.dont'), '1' => trans('dialog.do')) , $globalSettings->setting_counter_clan_on_off, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.AppOwner')}}:</h3>
    {{Form::label('setting_app_owner', trans('settings.AppOwner'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-3">
        {{-- AppOwner --}}
        {{Form::text('setting_app_owner', $globalSettings->setting_app_owner, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.setting_app_owner_email')}}:</h3>
    {{Form::label('setting_app_owner_email', trans('settings.setting_app_owner_email'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-3">
        {{-- AppOwner --}}
        {{Form::text('setting_app_owner_email', $globalSettings->setting_app_owner_email, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.site')}}:</h3>

        {{-- SiteName --}}
        {{Form::label('setting_site_name', trans('settings.SiteName') . '/' . trans('settings.SiteUrl'), array('class' => 'col-sm-3 col-md-6'))}}
    <div class="col-sm-2 col-md-3">
        {{Form::text('setting_site_name', $globalSettings->setting_site_name, array('class' => 'form-control'))}}<br>
        {{Form::input('url', 'setting_site_url', $globalSettings->setting_site_url, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.tax_global')}} in %:</h3>
    <div class="col-sm-2 col-md-3">
        {{-- SitPayment --}}
        {{Form::input('number', 'setting_global_tax', $globalSettings->setting_global_tax, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.currency')}}:</h3>
    <div class="col-sm-2 col-md-3">
        {{-- SitPayment --}}
        {{Form::select('setting_currency', trans('currency'), $globalSettings->setting_currency, array('class' => 'form-control'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.setting_bill_text')}}:</h3>
    <div class="col-sm-12 col-md-6">
        {{-- SitPayment --}}
        {{Form::textarea('setting_bill_text', $globalSettings->setting_bill_text, array('class' => 'form-control', 'id' => 'setting_bill_text'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.setting_bill_mail_text')}}:</h3>
    <div class="col-sm-12 col-md-6">
        {{-- SitPayment --}}
        {{Form::textarea('setting_bill_mail_text', $globalSettings->setting_bill_mail_text, array('class' => 'form-control', 'id' => 'setting_bill_mail_text'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('settings.setting_start_reservation_mail_text')}}:</h3>
    <div class="col-sm-12 col-md-6">
        {{-- Text for reservation reminders --}}
        {{Form::textarea('setting_start_reservation_mail_text', $globalSettings->setting_start_reservation_mail_text, array('class' => 'form-control', 'id' => 'setting_start_reservation_mail_text'))}}
    </div>
</div>
<div class="row">
    <h3>{{trans('reservation.periods')}}:</h3>

    {{-- BG-Image --}}
    {{Form::label('setting_login_bg_image', trans('settings.setting_login_bg_image'), array('class' => 'col-sm-6 col-md-6'))}}
    <div class="col-sm-1 col-md-1">
        <a class="btn btn-default" href="{{ route('calc-periods') }}">{{ trans('settings.periods') }}</a>
    </div>
</div>
<div class="row" style="margin-bottom: 20px;">
    <h3>{{trans('settings.title')}} {{trans('settings.go')}}</h3>
    <div class="col-sm-2 col-md-3">
        <button type="submit" class="btn btn-default" id="saveIt">{{trans('dialog.save')}}</button>

    </div>
 </div>
@include('logged.dialog.global_setting_save')
{{Form::close()}}
    @stop
    @section('scripts')
    @parent
    <script src="{{asset('libs/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('js/settings_help_init.min.js')}}"></script>
    @stop
