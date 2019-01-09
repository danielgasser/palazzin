@extends('layout.master')
@section('content')
    <h1>{{ trans('navigation.' . Route::getFacadeRoot()->current()->uri()) }}</h1>
@if (session('error')))
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
@if (count(Mail::failures()) > 0))
    <div>
        @foreach(Mail::failures() as $m)
        {{print_r($m)}}
        @endforeach
    </div>
    <h1>{{trans('remind.title')}}</h1>

@endif
<div class="row">
    {{ Form::open(array('action' => 'RemindersController@postRemind')) }}
    {{ csrf_field() }}
    {{ Form::label('email', trans('userdata.new_pass_email'), array('class' => 'col-sm-12 col-md-12')) }}
    <div class="col-sm-6 col-md-6">
        {{ Form::text('email', null, array('class' => 'form-control')) }}
    </div>
    <div class="col-sm-2 col-md-2">
        {{ Form::submit(trans('remind.send'), array('class' => 'btn btn-default')) }}
    </div>
    {{ Form::close() }}
</div>
@stop
