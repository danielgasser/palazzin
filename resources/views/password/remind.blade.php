@extends('layout.master')
@section('content')

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
@if (sizeof(Mail::failures() > 0))
<div>
    @foreach(Mail::failures() as $m)
    {!!print_r($m)!!}
    @endforeach
</div>
<h1>{!!trans('remind.title')!!}</h1>

@endif
<div class="row">
    {!! Form::open(array('action' => 'RemindersController@postRemind')) !!}
    {!! Form::label('email', trans('userdata.email'), array('class' => 'col-sm-2 col-md-2')) !!}
    <div class="col-sm-8 col-md-8">
        {!! Form::text('email', null, array('class' => 'form-control')) !!}
    </div>
    <div class="col-sm-2 col-md-2">
        {!! Form::submit(trans('remind.send'), array('class' => 'btn btn-default')) !!}
    </div>
    {!! Form::close() !!}
</div>
@stop