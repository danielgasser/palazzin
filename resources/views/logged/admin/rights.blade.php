@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp-rights.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>
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
    <script>
        var paginationLang = $.parseJSON('{!!json_encode((trans('pagination')))!!}'),
            autid = '{{Auth::id()}}';
    </script>
    <script src="{{asset('assets/js/inits/right_init.js')}}"></script>

    @stop

@stop
