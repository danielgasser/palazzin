@extends('layout.master')
@section('content')
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
        <div class="col-sm-4 col-md-4 col-xs-12 welcome-nav">
                @if(!Auth::check())
                <a href="{{URL::to('login')}}">{{trans('navigation.login')}}</a>
                @endif
                    <?php
                    $helper = explode('/', Route::getFacadeRoot()->current()->uri());
                    ?>
                    @if(Auth::check())
                        <a href="{{URL::to('/help/' . $helper[0])}}">{{trans('navigation.help')}}</a>
                @else
                    <a href="{{URL::to('/help')}}">{{trans('navigation.help')}}</a>
                @endif
        </div>
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
        <div class="col-sm-4 col-md-4 col-xs-12">
            @if(Auth::check())
            @include('layout.welcome-nav')
            @endif
        </div>
        <div class="col-sm-4 col-md-4 col-xs-12"></div>

        </div>
@endsection
