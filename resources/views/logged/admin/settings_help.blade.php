@extends('layout.master')
    @section('content')
<h1>{!!trans('settings.title_help')!!}</h1>
@if (sizeof(Session::get('error')) > 0)
    <div id="error-wrap">
        <div id="errors" class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <ul>
                <li><h3><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;{!!Session::get('error')!!}!</h3></li>
            </ul>
        </div>
    </div>

@endif

{!!Form::open(array('url' => 'admin/settings/help/add_topic', 'class' => 'form-inline', 'id' => 'save-topic-form', 'files' => false))!!}
<div class="row">
    <div class="col-xs-8 col-sm-8 col-md-8">
        {!!Form::text('help_topic', '', array('class' => 'form-control', 'style' => 'width: 100%', 'placeholder' => 'Neues Thema Handle'))!!}
        {!!Form::text('help_title', '', array('class' => 'form-control', 'style' => 'width: 100%', 'placeholder' => 'Neues Thema Titel'))!!}

    </div>
    <div class="col-xs-4 col-sm-4 col-md-4">
        {!!Form::submit('Thema ' . trans('dialog.save'), array('id' => 'saveItTopic', 'class' => 'btn btn-default'))!!}
    </div>

</div>
{!!Form::close()!!}
<hr>
{!!Form::open(array('url' => 'admin/settings/help', 'class' => 'form-inline', 'id' => 'save-settings-form', 'files' => true))!!}
<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9">
        <select id="getset" class="hundertpro form-control">
            <option value="">{!!trans('settings.choose')!!}</option>
        @foreach($helpSettings as $key => $val)
            <option value="{!!str_replace('/', '_', $val->help_topic)!!}">@if(Lang::has('navigation.' . $val->help_topic)){!!trans('navigation.' . $val->help_topic)!!}@else{!!$val->help_title!!}@endif</option>
        @endforeach
        </select>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3">
    {!!Form::submit(trans('dialog.save'), array('id' => 'saveItHelp', 'class' => 'btn btn-default'))!!}
    </div>

</div>
<div class="row">
    @foreach($helpSettings as $key => $val)
        <div id="toop_{!!str_replace('/', '_', $val->help_topic)!!}" class="col-xs-12 col-sm-12 col-md-12">
            {!!Form::textarea('help_text[]', $val->help_text, array('class' => 'form-control'))!!}
            {!!Form::hidden('id[]', $val->id)!!}
            {!!Form::hidden('help_topic[]', $val->help_topic)!!}
        </div>

    @endforeach
</div>
{!!Form::close()!!}
@include('logged.dialog.global_setting_save')
    @stop
    @section('scripts')
    @parent
    <script src="{!!asset('assets/js/funcs.js')!!}"></script>
    <script src="{!!asset('assets/js/funcs_new.js')!!}"></script>
    <script src="{!!asset('assets/js/browser_check.js')!!}"></script>
        <script src="{!!asset('assets/min/js/settings_help_init.min.js')!!}"></script>
    @stop
@stop