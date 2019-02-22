@extends('layout.master')
@section('content')
    <h1>{{trans('admin.stats.title')}}</h1>
    <div>
    @include('layout.stats_menu')
    </div>
@section('scripts')
    @parent
@stop

@stop

