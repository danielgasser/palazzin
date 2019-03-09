@extends('layout.master')
@section('content')
<div>
    <div class="table-responsive">
        <table id="pricelist" class="table">
            <thead>
                <tr>
                    <th>{{trans('roles.role_code')}}</th>
                    <th>{{trans('roles.role_description', ['n' => ''])}}</th>
                    <th>{{trans('roles.role_tax_annual', ['n' => 'CHF'])}}</th>
                    <th>{{trans('roles.role_tax_night', ['n' => 'CHF'])}}</th>
                    <th>{{trans('roles.role_tax_stock', ['n' => 'CHF'])}}</th>
                </tr>
            </thead>
            <tbody>
            @if(sizeof($allRoles) > 0)
                @foreach($allRoles as $role)
                    <tr>
                         <td>
                            {{$role->role_code}}
                         </td>
                         <td>{{trans('roles.' . $role->role_code)}}</td>
                         <td>{{$role->role_tax_annual == '0.00' ? '-' : $role->role_tax_annual}}</td>
                         <td>{{$role->role_tax_night == '0.00' ? '-' : $role->role_tax_night}}</td>
                         <td>{{$role->role_tax_stock == '0.00' ? '-' : $role->role_tax_stock}}</td>
                    </tr>
                 @endforeach
            @else
                <tr>
                    <td>
                        {{trans('errors.no-data', ['n' => 'e', 'd' => 'Rollen'])}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
    @section('scripts')
    @parent
    @stop

@stop
