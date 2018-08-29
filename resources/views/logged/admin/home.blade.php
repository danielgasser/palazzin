@extends('layout.master')
@section('content')
<h1>{!!trans('admin.home.title')!!}</h1>
<div class="row">
    <div class="col-sm-6 col-md-6">
    <ul>
        <li><a href="{!!URL::to('admin/users')!!}">{!!trans('navigation.admin/users')!!}</a></li>
        <li><a href="{!!URL::to('admin/users/add')!!}">{!!trans('navigation.admin/users/add')!!}</a></li>
        <li><a href="{!!URL::to('admin/roles')!!}">{!!trans('navigation.admin/roles')!!}</a></li>
        <li><a href="{!!URL::to('admin/rights')!!}">{!!trans('navigation.admin/rights')!!}</a></li>
        <li><a href="{!!URL::to('admin/reservations')!!}">{!!trans('navigation.admin/reservations')!!}</a></li>
        <li><a href="{!!URL::to('admin/bills')!!}">{!!trans('navigation.admin/bills')!!}</a></li>
        <li><a href="{!!URL::to('admin/stats')!!}">{!!trans('navigation.admin/stats')!!}</a></li>
        @if(User::isLoggedAdmin() == 1)
        <li><a href="{!!URL::to('admin/settings')!!}">{!!trans('navigation.admin/settings')!!}</a></li>
        @endif
    </ul>

</div>
</div>
@stop