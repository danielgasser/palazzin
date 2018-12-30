@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-sm-12">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
        <div class="col-sm-4 col-md-4 col-xs-12 welcome-nav">
                <a href="{!!URL::to('news')!!}">{!!trans('navigation.latest')!!}</a>
                <a href="{!!URL::to('login')!!}">{!!trans('navigation.login')!!}</a>
                
                    <?php
                    $helper = explode('/', Route::getFacadeRoot()->current()->uri());
                    ?>
                    @if(Auth::check())
                        <a href="{!!URL::to('/help/' . $helper[0])!!}">{!!trans('navigation.help')!!}</>
                @else
                    <a href="{!!URL::to('/help')!!}">{!!trans('navigation.help')!!}</a>
                @endif
        </div>
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
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

