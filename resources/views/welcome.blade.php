@extends('layout.master')
@section('content')
</div>
    <div class="row">
        <div class="col-sm-2 col-md-2 col-xs-12"></div>
        <div class="col-sm-8 col-md-8 col-xs-12 welcome-nav">
            @if(Auth::check())
            @include('layout.welcome-nav')
            @endif
        </div>
        <div class="col-sm-2 col-md-2 col-xs-12"></div>

        </div>
@endsection
