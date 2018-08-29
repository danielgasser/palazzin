@extends('layout.master')
@section('content')

<h1>{!!trans('admin.user.title')!!}</h1>
<div>    {!!Form::open(array('url' => 'userlist', 'class' => 'form-inline', 'style' => 'margin-bottom: 2em', 'role'=> 'form', 'method' => 'post'))!!}
    <div class="row">
        <div class="col-sm-3 col-md-3">
            {!!Form::text('search_user', Input::old('search_user'), array('class' => 'form-control', 'id' => 'search_user', 'placeholder' => trans('admin.user.etc')))!!}
            <br>{!!trans('errors.data')!!}: <span id="records_no">{!!sizeof($allUsers)!!}</span> von Total {!!sizeof($allUsers)!!}
        </div>
        {{-- clan --}}
        {!!Form::label('clan_search', trans('userdata.clan'), array('class' => 'col-sm-1 col-md-1'))!!}
        <div class="col-sm-2 col-md-2">
            {!!Form::select('clan_search', $clans, Input::old('clan_search'), array('class' => 'form-control'))!!}
        </div>
        {{-- new --}}
        {!!Form::label('search_new', 'Registriert', array('class' => 'col-sm-1 col-md-1'))!!}
        <div class="col-sm-2 col-md-2">
            {!!Form::select('search_new', ['' => trans('dialog.all'), 'registriert', 'neu'], Input::old('search_new'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-3 col-md-3">
            {!!Form::submit('go')!!}
            <a href="{!!URL::to(Route::getFacadeRoot()->current()->uri())!!}" class="btn btn-default">{!!trans('dialog.all')!!}</a>

            {!!Form::close()!!}
        </div><pre>

        {!!dd($allUsers)!!}</pre>
</div>
@include('logged.dialog.user_delete')
@section('scripts')
    @parent
    <script src="{!!asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')!!}"></script>
    <script src="{!!asset('assets/min/js/userlist_init.min.js')!!}"></script>
    <script>
        var ss = 'ASC',
                a = '#allReservations',
                locale = '{!!Lang::get('formats.langlangjs')!!}',
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                langUser = {!!json_encode(array_merge(Lang::get('userdata'), Lang::get('profile')))!!},
                langRole = {!!json_encode(Lang::get('roles'))!!},
                cols = $('th'),
                yl = [],
                settings = {!!App::make('GlobalSettings')->getSettings()!!},
                ml = [],
                route = '{!!Route::getCurrentRoute()->getPath()!!}';
    </script>
    <script src="{!!asset('assets/js/inits/search_user_tables_init.js')!!}"></script>
    <script src="{!!asset('assets/min/js/tables.min.js')!!}"></script>
@stop

@stop