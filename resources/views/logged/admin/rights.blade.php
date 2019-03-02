@extends('layout.master')
@section('content')
    <div>
    {{Form::model(null, array('action' => 'RightController@searchRights'))}}
    {{Form::text('searchAllRights')}}
    {{Form::submit(trans('dialog.search'), array('class' => 'btn btn-default','id' => 'searchIt'))}}
    <button class="btn btn-default">{{trans('dialog.all')}}</button>
    {{Form::close()}}
    </div>
    <div class="table-responsive">
        <table id="users" class="table tablesorter">
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
    </div>
</div>
    @section('scripts')
    @parent
        <script src="/assets/js/tablesorter/jquery.tablesorter.min.js"></script>
        <script src="{{asset('assets/min/js/admin.min.js')}}"></script>
    @stop

@stop
