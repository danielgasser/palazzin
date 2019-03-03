@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp-roles.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>
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
    <script>
        let options = {
            responsive: true,
            autoWidth: false,
            fixedHeader: {
                header: true,
                footer: true
            },
            order: [
                1,
                'asc'
            ],
            language: {
                paginate: {
                    first: '{{trans('pagination.first')}}',
                    previous: '{{trans('pagination.previous')}}',
                    next: '{{trans('pagination.next')}}',
                    last: '{{trans('pagination.last')}}'
                },
                search: '{{trans('dialog.search')}}',
                info: '{{trans('pagination.info')}}',
                sLengthMenu: '{{trans('pagination.length_menu')}}'
            },
            fnDrawCallback: function () {
            },
            lengthChange: false
        };
        $(function () {
            $('#roles').dataTable(options)
        });
    </script>
    @stop

@stop
