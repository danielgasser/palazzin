@extends('layout.master')
@section('content')
<div>
     @if(sizeof(Session::get('error')) > 0)
        <div id="error-wrap">
           <div id="errors" class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <ul>
                    <li><h3><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;{{Session::get('error')}}!</h3></li>
                </ul>
           </div>
        </div>
     @endif
</div>
<h1>{{trans('reset.title')}}</h1>
<div class="row">
    {{ Form::open(array('action' => 'RemindersController@postReset')) }}
    {{ Form::hidden('token', $token) }}
    {{ Form::label('email', trans('userdata.email'), array('class' => 'col-sm-1 col-md-1')) }}
    <div class="col-sm-3 col-md-3">
        {{ Form::email('email', null, array('class' => 'form-control', 'placeholder' => trans('userdata.email'))) }}
    </div>
    {{ Form::label('password', trans('userdata.pass'), array('class' => 'col-sm-1 col-md-1')) }}
    <div class="col-sm-3 col-md-3">
        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('userdata.pass'))) }}
    </div>
    {{ Form::label('password_confirmation', trans('userdata.pass_confirm'), array('class' => 'col-sm-1 col-md-1')) }}
     <div class="col-sm-3 col-md-3">
        {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => trans('userdata.pass_confirm'))) }}
        {{ Form::submit(trans('reset.send'), array('class' => 'btn btn-default')) }}
    </div>
    {{ Form::close() }}
</div>
@stop
