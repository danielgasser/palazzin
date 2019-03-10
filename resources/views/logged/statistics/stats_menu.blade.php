@extends('layout.master')
@section('header')
    @parent
    <style>
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: 1px solid #333;

        }

    </style>
@stop
@section('content')
    <h1>{{trans('admin.stats.title')}}</h1>
    <div>
    @include('layout.stats_menu')
    </div>
@section('scripts')
    @parent
@stop

@stop

