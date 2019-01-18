@extends('layout.master')
@section('content')
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
        <div class="col-sm-4 col-md-4 col-xs-12 welcome-nav">
                <a href="{{URL::to('news')}}">{{trans('navigation.latest')}}</a>
                @if(!Auth::check())
                <a href="{{URL::to('login')}}">{{trans('navigation.login')}}</a>
                @endif
                    <?php
                    $helper = explode('/', Route::getFacadeRoot()->current()->uri());
                    ?>
                    @if(Auth::check())
                        <a href="{{URL::to('/help/' . $helper[0])}}">{{trans('navigation.help')}}</>
                @else
                    <a href="{{URL::to('/help')}}">{{trans('navigation.help')}}</a>
                @endif
        </div>
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
    </div>
@section('scripts')
    @parent
    <script>
        var oldie = '{{$isOldWin}}';
        $(document).ready(function(){
            if (oldie === '1') {
                $('#old_ie').show();
            }
        })
        window.localStorage.clear();
    </script>
@stop
@stop

