@extends('layout.master')
@section('content')

</div>
    {{Form::model($role, array('url' => array('admin/roles/edit', $role->id)))}}
        {{Form::hidden('id', $role->id)}}
        <fieldset>
            <legend>{{trans('roles.role_description', ['n' => ''])}}</legend>
            <div class="row">
                {{Form::label('role_code', trans('roles.role_code'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('role_code', $role->role_code, array('class' => 'form-control', 'disabled'))}}
                </div>
                {{Form::label('role_description', trans('roles.role_description', ['n' => '']), array('class' => 'col-sm-2 col-md-1'))}}
                    <div class="col-sm-4 col-md-5">
                {{Form::text('role_description', trans('roles.' . $role->role_code), array('class' => 'form-control', 'disabled'))}}
                </div>
            </div>
            <div class="row">
                {{Form::label('role_tax_annual', trans('roles.role_tax_annual', ['n' => 'CHF']), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('role_tax_annual', $role->role_tax_annual, array('class' => 'form-control required'))}}
                </div>
                {{Form::label('role_tax_night', trans('roles.role_tax_night', ['n' => 'CHF']), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::text('role_tax_night', $role->role_tax_night, array('class' => 'form-control required'))}}
                </div>
            </div>

            <div class="row">
            @if($role->role_code == 'AG')
                {{Form::label('role_tax_stock', trans('roles.role_tax_stock', ['n' => 'CHF']), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::text('role_tax_stock', $role->role_tax_stock, array('class' => 'form-control required'))}}
                </div>
            @endif
                {{Form::label('role_guest', trans('roles.role_guest'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('role_guest', [trans('dialog.n'), trans('dialog.y')], $role->role_guest, array('class' => 'form-control required'))}}
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{{trans('rights.right')}}</legend>
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{trans('dialog.delete')}}</th>
                                    <th>{{trans('rights.right')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($role->rights as $right)
                               <tr>
                                    <td>
                                        <span  id="deleteRight_{{$right->id}}" class="btn btn-sm glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </td>
                                    <td>
                                        {{trans('rights.' . $right->right_code)}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
               {{Form::label('right_id', trans('rights.right_new'), array('class' => 'col-sm-2 col-md-2'))}}
                <div class="col-sm-4 col-md-4">
               {{Form::select('right_id', $allRights, trans('dialog.select'), array('class' => 'form-control'))}}
                </div>
                <div class="col-sm-3 col-md-4">
                    <button class="btn btn-default" id="addRight">{{trans('dialog.add')}}</button>
                </div>
                <div class="col-sm-3 col-md-2">

                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{{trans('profile.actions')}}</legend>
            <div class="row">
                <div class="col-sm-3 col-md-4">
                    {{Form::submit(trans('dialog.save'), ['class' => 'btn btn-default', 'id' => 'saveRole'])}}
                </div>
                <div class="col-sm-3 col-md-2">

                </div>
            </div>
        </fieldset>
    {{Form::close()}}
    @include('logged.dialog.right_delete')
    @section('scripts')
    @parent
        <script>
            var role_rights = '{{URL::to('admin/roles/rights')}}',
                role_right_delete = '{{URL::to('admin/roles/rights/delete')}}',
        </script>
        <script src="{{asset('assets/min/js/admin.min.js')}}"></script>
    @stop
@stop
