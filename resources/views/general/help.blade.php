@extends('layout.master')
@section('content')
<h1 class="h1-help">{!!trans('help.title')!!}</h1>
<div id="help-container" class="row">
    @if(Auth::check())
    <div id="other-help" class="col-sm-12 col-md-12">
            {!! Form::open(array('id' => 'changeTopics'), array('class' => 'form-inline')) !!}
            {!!Form::label('help_topic', trans('help.choose'))!!}
            {!!Form::select('help_topic', $routes, null, ['class' => 'form-control'])!!}
            {!! Form::close() !!}
    </div>
    @endif
    @if(Auth::guest())
        @if(strlen($helptext) > 0)
                <div class="topics col-sm-12 col-md-12">
                    <div id="topic_text">
                        {!!$helptext ?? ''!!}
                    </div>
                </div>
        @else
                <div class="topics col-sm-12 col-md-12">
            <h3>{!!trans('help.login')!!}:</h3>
        </div>
        <div class="topics col-sm-12 col-md-12">
            <ol>
            @foreach(trans('help.login_text') as $lt)
                <li>{!!$lt!!}</li>
            @endforeach
            </ol>
        </div>
            @endif
    @endif
    @if(Auth::check())
        {{-- Home --}}
            <div class="topics col-sm-12 col-md-12">
                <div id="topic_text">
                    {!!$helptext!!}
                </div>
            </div>
    @endif
    <div>
    <a href="{!!URL::previous()!!}">{!!trans('dialog.back', ['to' => ''])!!}</a>
    </div>
</div>
    @section('scripts')
    @parent
        <script>
        var url = '{!!$showUrl!!}';
        </script>
         <script src="{!!asset('assets/js/inits/help_init.js')!!}"></script>
   @stop
@stop