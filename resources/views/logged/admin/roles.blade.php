@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/datatables_roomapp-roles.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>
    <style>
        .dataTables_wrapper {
            margin: 0;
            border: none;
        }
        .form-control {
            width: auto;
        }
    </style>
@stop
@section('content')
        <table id="roles">
            <thead>
                <tr>
                    <th></th>
                    <th>{{trans('roles.role_code')}}</th>
                    <th>{{trans('roles.role_description', ['n' => ''])}}</th>
                    <th>{{trans('roles.role_tax_annual', ['n' => 'CHF'])}}</th>
                    <th>{{trans('roles.role_tax_night', ['n' => 'CHF'])}}</th>
                    <th>{{trans('roles.role_tax_stock', ['n' => 'CHF'])}}</th>
                    <th>{{trans('roles.role_guest')}}</th>
                    <th>{{trans('rights.right')}}</th>
                </tr>
            </thead>
            <tbody>
            @if(sizeof($allRoles) > 0)
                @foreach($allRoles as $role)
                    <tr>
                         <td>
                         @if($role->role_code != 'ADMIN')
                            <a href="{{URL::to('admin/roles/edit') . '/' . $role->id}}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                         @endif
                         </td>
                         <td>
                            {{$role->role_code}}
                         </td>
                         <td>{{trans('roles.' . $role->role_code)}}</td>
                         <td>{{$role->role_tax_annual}}</td>
                         <td>{{$role->role_tax_night}}</td>
                         <td>{{$role->role_tax_stock}}</td>
                         <td>@if($role->role_guest == 1)
                                {{trans('dialog.y')}}
                             @else
                                {{trans('dialog.n')}}
                             @endif
                         </td>
                         <td>
                            <div class="table-inside">
                                <table class="table">
                                 <tbody>
                                    @foreach($role->rights as $key => $rights)
                                    <tr>
                                         <td>
                                             {{trans('rights.' . $rights->right_code)}}
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
                        {{trans('errors.no-data', ['n' => 'e', 'd' => 'Rollen'])}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    @section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script>
        var autid = '{{Auth::id()}}';
    </script>
    <script src="{{asset('js/role_init.min.js')}}"></script>


    @stop

@stop
