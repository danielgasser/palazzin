@extends('layout.master')
@section('content')
<div id="noview">
    <?php
    //die('<h1>Die Seite ist in Bearbeitung. Bitte kommt später wieder...</h1>')
    ;?>
    {!!trans('login.RoomApp_Welcome')!!}
      {!! Form::open(array('url' => '/'), array('class' => 'form-inline')) !!}
        {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            <h1>{!!trans('login.login')!!}</h1>
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            {!! Form::label('user_login_name', trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email')) !!}
            {{--- Changed for local dev ---}}
            {!! Form::text('user_login_name', 'daniel.gasser', array('class' => 'form-control', 'placeholder' => trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email'))) !!}
            {!! Form::hidden('new_comment', (isset($_GET['new_comment'])) ? $_GET['new_comment'] : null, array('class' => 'form-control', 'placeholder' => trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email'))) !!}
            {!! Form::hidden('new_comment_user_id', (isset($_GET['new_comment_user_id'])) ? $_GET['new_comment_user_id'] : null, array('class' => 'form-control', 'placeholder' => trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email'))) !!}
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            {!! Form::label('password',  trans('userdata.pass')) !!}
            {{--- Changed for local dev ---}}
            B7HDVDJQclGR
            {!! Form::password('password', array('class' => 'form-control', 'placeholder' => trans('userdata.pass'))) !!}
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4 stay_tuned">
            <label>
              <input class="checkbox" name="stay_tuned" id="stay_tuned" type="checkbox" /><span style="color: #333">{!!trans('login.stay')!!}</span>
            </label>
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            {!! Form::submit(trans('login.login'), ['class' => 'btn btn-default hundertpro']) !!}
            {!! Form::close() !!}
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            <a href="{!!URL::to('password')!!}">{!!trans('login.forgot')!!}?</a><br>
            <a href="{!!URL::to('help/pl')!!}">{!!trans('login.login_prob')!!}?</a>
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
</div>
@section('scripts')
    @parent
    <script>
        var oldie = '{!!$isOldWin!!}';
        $(document).ready(function(){
            if (oldie === '1') {
                $('#old_ie').modal({backdrop: 'static', keyboard: false})
            }
        })
        window.localStorage.clear();
    </script>
    @stop
@stop

