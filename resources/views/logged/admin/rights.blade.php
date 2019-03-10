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
        <table id="rights" class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>{{trans('rights.right_code')}}</th>
                    <th>{{trans('rights.right_description', ['n' => ''])}}</th>
                </tr>
            </thead>
            <tbody>
            @if(sizeof($allRights) > 0)
                @foreach($allRights as $right)
                    <tr>
                         <td>
                            <a href="{{URL::to('admin/rights/edit') . '/' . $right->id}}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                         </td>
                         <td>
                            {{$right->right_code}}
                         </td>
                         <td>{{trans('rights.' . $right->right_code)}}</td>
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
    <script src="{{asset('js/right_init.min.js')}}"></script>

    @stop

@stop
