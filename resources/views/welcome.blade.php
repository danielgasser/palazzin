@extends('layout.master')
@section('content')
        <div class="col-sm-4 col-md-4 col-xs-12"></div>
        <div class="col-sm-4 col-md-4 col-xs-12">
                @if(!Auth::check())
                <a href="{{URL::to('login')}}">{{trans('navigation.login')}}</a>
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
