@extends('layout.master')
@section('content')
<h1>{!!trans('admin.roles.title')!!}</h1>
<div>
    <h3>{!!trans('admin.roles.etc')!!}:</h3>
    <div>
    {!!Form::model(null, array('action' => 'RoleController@searchRoles'))!!}
    {!!Form::text('searchAllRoles')!!}
    {!!Form::submit(trans('dialog.search'), array('class' => 'btn btn-default','id' => 'searchIt'))!!}
    <button class="btn btn-default">{!!trans('dialog.all')!!}</button>
    {!!Form::close()!!}
    </div>
    <div class="table-responsive">
        <table id="users" class="table tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th>{!!trans('roles.role_code')!!}</th>
                    <th>{!!trans('roles.role_description', ['n' => ''])!!}</th>
                    <th>{!!trans('roles.role_tax_annual', ['n' => 'CHF'])!!}</th>
                    <th>{!!trans('roles.role_tax_night', ['n' => 'CHF'])!!}</th>
                    <th>{!!trans('roles.role_tax_stock', ['n' => 'CHF'])!!}</th>
                    <th>{!!trans('roles.role_guest')!!}</th>
                    <th>{!!trans('rights.right')!!}</th>
                </tr>
            </thead>
            <tbody>
            @if(sizeof($allRoles) > 0)
                @foreach($allRoles as $role)
                    <tr>
                         <td>
                         @if($role->role_code != 'ADMIN')
                            <a href="{!!URL::to('admin/roles/edit') . '/' . $role->id!!}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                         @endif
                         </td>
                         <td>
                            {!!$role->role_code!!}
                         </td>
                         <td>{!!trans('roles.' . $role->role_code)!!}</td>
                         <td>{!!$role->role_tax_annual!!}</td>
                         <td>{!!$role->role_tax_night!!}</td>
                         <td>{!!$role->role_tax_stock!!}</td>
                         <td>@if($role->role_guest == 1)
                                {!!trans('dialog.y')!!}
                             @else
                                {!!trans('dialog.n')!!}
                             @endif
                         </td>
                         <td>
                            <div class="table-inside">
                                <table class="table">
                                 <tbody>
                                    @foreach($role->rights as $key => $rights)
                                    <tr>
                                         <td>
                                             {!!trans('rights.' . $rights->right_code)!!}
                                         </td>
                                     </tr>
                                     @endforeach
                                 </tbody>
                                </table>
                            </div>
                         </td>
                    </tr>
                 @endforeach
            @else
                <tr>
                    <td>
                        {!!trans('errors.no-data', ['n' => 'e', 'd' => 'Rollen'])!!}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
    @section('scripts')
    @parent
        <script src="{!!asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')!!}"></script>
        <script src="{!!asset('assets/min/js/admin.min.js')!!}"></script>
    @stop

@stop
