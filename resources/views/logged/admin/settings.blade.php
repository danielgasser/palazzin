@extends('layout.master')
    @section('content')
<h1>{!!trans('settings.title')!!}</h1>
<h3><a href="{!!URL::to('admin/settings/help')!!}">{!!trans('settings.title_help')!!}</a></h3>
    {!!Form::model($setting, array('url' => 'admin/settings', 'class' => 'form-inline', 'id' => 'save-settings-form', 'files' => true))!!}
    {!!Form::hidden('id', $globalSettings->id)!!}
{!! csrf_field() !!}

    <h3>{!!trans('settings.calendar')!!}:</h3>
<div class="row">
        {{-- CalendarStart --}}
    {!!Form::label('setting_calendar_start', trans('settings.CalendarStart'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-9 col-md-6">
        {!!Form::input('text', 'setting_calendar_start', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $globalSettings->setting_calendar_start)->formatLocalized('%Y-%m-%d'), array('class' => 'form-control datepicker date_type', 'data-provide' => 'datepicker'))!!}
    </div>
</div>
<div class="row">
{{-- CalendarDuration --}}
    {!!Form::label('setting_calendar_duration', trans('settings.CalendarDuration'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-3 col-md-3">
        {!!Form::input('number', 'setting_calendar_duration', $globalSettings->setting_calendar_duration, array('class' => 'form-control', 'style' => 'width: 56px'))!!}
    </div>
     {{-- CalendarStartingClan --}}
     {!!Form::label('setting_num_bed', trans('settings.setting_num_bed'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-3 col-md-3">
        {!!Form::input('number', 'setting_num_bed', $globalSettings->setting_num_bed, array('class' => 'form-control', 'style' => 'width: 66px'))!!}
    </div>
 </div>
 <div class="row">
     {!!Form::label('setting_starting_clan', trans('settings.CalendarStartingClan'), array('class' => 'col-sm-3 col-md-6'))!!}
   <div class="col-sm-2 col-md-2">
        {!!Form::select('setting_starting_clan', $clans, $globalSettings->setting_starting_clan, array('class' => 'form-control'))!!}
    </div>
</div>
<div class="row">
        {!!Form::label('setting_reminder_days', trans('settings.setting_reminder_days'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {!!Form::text('setting_reminder_days', $globalSettings->setting_reminder_days, array('class' => 'form-control'))!!}
    </div>
</div>
<div class="row">
        {!!Form::label('setting_num_counter_clan_days', trans('settings.setting_num_counter_clan_days'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {!!Form::text('setting_num_counter_clan_days', $globalSettings->setting_num_counter_clan_days, array('class' => 'form-control'))!!}
    </div>
</div>
<div class="row">
    {!!Form::label('', trans('settings.setting_num_counter_days_on_off', array('days' => $globalSettings->setting_num_counter_clan_days)), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {!!Form::select('setting_num_counter_days_on_off', array('0' => trans('dialog.dont'), '1' => trans('dialog.do')) , $globalSettings->setting_num_counter_days_on_off, array('class' => 'form-control'))!!}
    </div>
</div>
<div class="row">
    {!!Form::label('', trans('settings.setting_counter_clan_on_off'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-1">
        {{-- AppOwner --}}
        {!!Form::select('setting_counter_clan_on_off', array('0' => trans('dialog.dont'), '1' => trans('dialog.do')) , $globalSettings->setting_counter_clan_on_off, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
      <h3>{!!trans('settings.AppOwner')!!}:</h3>
<div class="row">
        {!!Form::label('setting_app_owner', trans('settings.AppOwner'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-3">
        {{-- AppOwner --}}
        {!!Form::text('setting_app_owner', $globalSettings->setting_app_owner, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
      <h3>{!!trans('settings.setting_app_owner_email')!!}:</h3>
<div class="row">
        {!!Form::label('setting_app_owner_email', trans('settings.setting_app_owner_email'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-3">
        {{-- AppOwner --}}
        {!!Form::text('setting_app_owner_email', $globalSettings->setting_app_owner_email, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.setting_app_logo')!!}:</h3>
<div class="row">

    {{-- BG-Image --}}
    {!!Form::label('setting_app_logo', trans('settings.setting_app_logo'), array('class' => 'col-sm-6 col-md-6'))!!}
    <div class="col-sm-2 col-md-2">
        <img src="{!!$globalSettings->setting_app_logo!!}" style="background-color: white;" />
        {!!Form::file('setting_app_logo', array('class' => 'form-control'))!!}
        {!!Form::hidden('setting_app_logo', $globalSettings->setting_app_logo)!!}
    </div>
</div>
<hr>

<h3>{!!trans('settings.site')!!}:</h3>
<div class="row">

        {{-- SiteName --}}
        {!!Form::label('setting_site_name', trans('settings.SiteName') . '/' . trans('settings.SiteUrl'), array('class' => 'col-sm-3 col-md-6'))!!}
    <div class="col-sm-2 col-md-3">
        {!!Form::text('setting_site_name', $globalSettings->setting_site_name, array('class' => 'form-control'))!!}<br>
        {!!Form::input('url', 'setting_site_url', $globalSettings->setting_site_url, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.PaymentMethod')!!}:</h3>
<div class="row">
    @foreach(explode(',', $globalSettings->setting_payment_methods) as $key => $s)
        <div class="col-sm-4 col-md-4">
        {!!Form::label('setting_payment_methods', 'N° ' . ($key + 1))!!}
        {{-- SitPayment --}}
        {!!Form::text('setting_payment_methods[]', $s, array('class' => 'form-control'))!!}
        </div>
    @endforeach
</div>
<hr>
<h3>{!!trans('settings.tax_global')!!} in %:</h3>
<div class="row">
    <div class="col-sm-2 col-md-3">
        {{-- SitPayment --}}
        {!!Form::input('number', 'setting_global_tax', $globalSettings->setting_global_tax, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.currency')!!}:</h3>
<div class="row">
    <div class="col-sm-2 col-md-3">
        {{-- SitPayment --}}
        {!!Form::select('setting_currency', trans('currency'), $globalSettings->setting_currency, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.setting_bill_text')!!}:</h3>
<div class="row">
    <div class="col-sm-12 col-md-6">
        {{-- SitPayment --}}
        {!!Form::textarea('setting_bill_text', $globalSettings->setting_bill_text, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.setting_bill_mail_text')!!}:</h3>
<div class="row">
    <div class="col-sm-12 col-md-6">
        {{-- SitPayment --}}
        {!!Form::textarea('setting_bill_mail_text', $globalSettings->setting_bill_mail_text, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.setting_start_reservation_mail_text')!!}:</h3>
<div class="row">
    <div class="col-sm-12 col-md-6">
        {{-- Text for reservation reminders --}}
        {!!Form::textarea('setting_start_reservation_mail_text', $globalSettings->setting_start_reservation_mail_text, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.posts')!!}:</h3>
<div class="row">

    {{-- Posts --}}
    {!!Form::label('setting_editable_record_time', trans('settings.setting_editable_record_time'), array('class' => 'col-sm-6 col-md-6'))!!}
    <div class="col-sm-1 col-md-1">
        {!!Form::text('setting_editable_record_time', $globalSettings->setting_editable_record_time, array('class' => 'form-control'))!!}
    </div>
</div>
<div class="row">
    {{-- Comments --}}
    {!!Form::label('setting_num_comments', trans('settings.setting_num_comments'), array('class' => 'col-sm-6 col-md-6'))!!}
    <div class="col-sm-1 col-md-1">
        {!!Form::text('setting_num_comments', $globalSettings->setting_num_comments, array('class' => 'form-control'))!!}
    </div>
</div>
<hr>
<h3>{!!trans('settings.login_bg_img')!!}:</h3>
<div class="row">

    {{-- BG-Image --}}
    {!!Form::label('setting_login_bg_image', trans('settings.setting_login_bg_image'), array('class' => 'col-sm-6 col-md-6'))!!}
    <div class="col-sm-1 col-md-1">
        <img src="{!!$globalSettings->setting_login_bg_image!!}" width="250" />
        {!!Form::file('setting_login_bg_image', array('class' => 'form-control'))!!}
        {!!Form::hidden('setting_login_bg_image_none', $globalSettings->setting_login_bg_image)!!}
    </div>
</div>
<hr>
<h3>{!!trans('reservation.periods')!!}:</h3>
<div class="row">

    {{-- BG-Image --}}
    {!!Form::label('setting_login_bg_image', trans('settings.setting_login_bg_image'), array('class' => 'col-sm-6 col-md-6'))!!}
    <div class="col-sm-1 col-md-1">
        <a class="btn btn-default" href="{!! route('calc-periods') !!}">{!! trans('settings.periods') !!}</a>
    </div>
</div>
<hr>
<h3>{!!trans('settings.title')!!} {!!trans('settings.go')!!}</h3>
<div class="row">
    <div class="col-sm-2 col-md-3">
        <button type="button" class="btn btn-default" id="saveIt">{!!trans('dialog.save')!!}</button>

    </div>
 </div>
@include('logged.dialog.global_setting_save')
{!!Form::close()!!}
    @stop
    @section('scripts')
    @parent
        <script src="{!!asset('assets/min/js/admin.min.js')!!}"></script>
    @stop
