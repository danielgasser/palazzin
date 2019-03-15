@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/datatables_roomapp.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>
    <style>
        .form-control {
            margin: 0;
        }
        .dataTables_wrapper {
            border: none;
        }
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: none !important;
        }
    </style>

@stop

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
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script src="{{asset('js/pricelist_init.min.js')}}"></script>
    @stop

@stop
