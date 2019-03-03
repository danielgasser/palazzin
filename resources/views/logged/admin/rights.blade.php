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
                $('#rights').dataTable(options)
            });
        </script>
    @stop

@stop
