@extends('layout.master')
    @section('header')
        @parent
        <style>
        .table>tbody>tr>td {
            border: none !important;
        }
    </style>
    @stop
@section('content')

</div>
    {{Form::model($user, array('url' => 'admin/users/add', 'class' => '', 'files' => true, 'id' => 'UserAdd'))}}
        <fieldset>
            <legend>{{trans('profile.names')}}</legend>
            <div class="row">
                {{-- name --}}
                {{Form::label('user_name', trans('userdata.user_name'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('user_name', old('user_name'), array('class' => 'form-control required' . ' ' . trans('userdata.user_name')))}}
                </div>
                {{-- first name --}}
                {{Form::label('user_first_name', trans('userdata.user_first_name'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                    <div class="col-sm-4 col-md-5">
                {{Form::text('user_first_name', old('user_first_name'), array('class' => 'form-control required' . ' ' . trans('userdata.user_first_name')))}}
                </div>
            </div>
            <div class="row">
                {{-- login name --}}
                {{Form::label('user_login_name', trans('userdata.user_login_name'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('user_login_name_show', $user->user_login_name, array('class' => 'form-control', 'disabled', 'id' => 'user_login_name_show'))}}
                    {{Form::hidden('user_login_name', $user->user_login_name, array('class' => 'form-control', 'id' => 'user_login_name'))}}
                </div>
                {{-- clan --}}
                {{Form::label('clan_id', trans('userdata.clan'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('clan_id', [trans('dialog.select')] + $clans, old('clan_id'), array('class' => 'form-control required' . ' ' . trans('userdata.clan')))}}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                </div>
                {{-- family --}}
                {{Form::label('family_code', trans('userdata.halfclan'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('family_code', [trans('dialog.select_what', ['n' => 'Stamm'])], old('family_code'), array('class' => 'form-control required' . ' ' . trans('userdata.halfclan'), 'disabled' => 'disabled'))}}
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{{trans('userdata.email')}}</legend>
             <div class="row">
                 {{-- family --}}
                 {{Form::label('email', trans('userdata.email'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                 <div class="col-sm-4 col-md-5">
                 {{Form::email('email', old('email'), array('class' => 'form-control required' . ' ' . trans('userdata.email')))}}
                 </div>
                   <div class="col-sm-6 col-md-6">
                   </div>
           </div>
       </fieldset>
        <fieldset>
            <legend>{{trans('navigation.admin/users') . ' ' . trans('dialog.activate')}}</legend>
            <div class="row">
                {{-- active --}}
                {{Form::label('user_active', trans('userdata.user_active'), array('class' => 'col-sm-2 col-md-1 requ'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('user_active', ['x' => trans('dialog.select'), '0' => trans('dialog.passive'), '1' => trans('dialog.active')], old('user_active'), array('class' => 'form-control required'))}}
                </div>
                 <div class="col-sm-6 col-md-6">
                 </div>
           </div>
        </fieldset>
         <fieldset>
             <legend>{{trans('profile.roles')}}</legend>
             <div class="table-responsive">
                 <table class="table">
                    <thead>
                        <tr>
                            <th>{{trans('dialog.delete')}}</th>
                            <th>{{trans('roles.role_description', ['n' => ''])}}</th>
                            <th>{{trans('roles.role_tax_annual', array('n' => 'CHF '))}}</th>
                            <th>{{trans('roles.role_tax_night', array('n' => 'CHF '))}}</th>
                            <th>{{trans('roles.role_tax_stock', array('n' => 'CHF '))}}</th>
                            <th>{{trans('rights.right')}}</th>
                        </tr>
                    </thead>
                    <tbody id="roles">
                    {{Form::hidden('role_id_add', old('role_id_add'), ['id' => 'role_id_add'])}}
                       <tr id="no_role">
                            <td>
                                {{trans('errors.no-data', ['n' => 'e', 'd' => 'Rollen'])}}
                            </td>
                        </tr>
                    </tbody>
                 </table>
             </div>
             <div class="row">
                {{Form::label('role_id', trans('roles.role_new'), array('class' => 'col-sm-2 col-md-2 requ'))}}
                 <div class="col-sm-4 col-md-4">
                {{Form::select('role_id', $allRoles, null, array('class' => 'form-control required' . ' ' . trans('roles.role_description', ['n' => ''])))}}
                 </div>
                 <div class="col-sm-6 col-md-6">
                    <button class="btn btn-default" id="add_role">{{trans('dialog.add')}}</button>
                 </div>
              </div>
         </fieldset>
        <fieldset>
            <legend>{{trans('userdata.email')}} {{trans('remind.user_send_man')}}</legend>
            <div class="row">
                {{-- active --}}
                {{Form::label('send', trans('userdata.email'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('send', $user->email, array('class' => 'form-control', 'disabled' => 'disabled'))}}
                </div>
                <div class="col-sm-6 col-md-6">
                    <a href="{{URL::to('admin/users/add/sendnew')}}/{{$user->email}}" class="btn btn-default" id="sendLink" disabled="disabled">{{trans('dialog.send')}}</a>
                </div>
            </div>
        </fieldset>

        <fieldset>
          <legend>{{trans('profile.actions')}}</legend>
              <div class="row">
                <div class="col-sm-6 col-md-2">
                      {{Form::submit(trans('dialog.save'), array('class' => 'btn btn-default'))}}
                </div>
                <div class="col-sm-6 col-md-2">
                    <!-- button type="button" id="profilePrint" class="btn btn-default">{{trans('dialog.print')}}</button -->
                </div>
                <div class="col-sm-6 col-md-2">

                </div>
              </div>
          </fieldset>
    {{Form::close()}}
    @include('logged.dialog.role_delete')
    @section('scripts')
    @parent
        <script>
           var user_delete = '{{URL::to('admin/users/edit/delete')}}',
               change_clan = '{{URL::to('admin/users/changeclan')}}',
               user_activate = '{{URL::to('admin/users/activate')}}',
               user_id = '{{$user->id}}',
               old_input = JSON.parse('{!!json_encode(Session::getOldInput()) !!}'),
               add_role = '{{URL::to('admin/users/addrole')}}',
               role_delete = '{{URL::to('admin/users/edit/delete')}}',
               families = {!!json_encode($families)!!},
               sendUserMail = '{{URL::to('admin/users/add/sendnew')}}/',
               route = '{{Route::getFacadeRoot()->current()->uri()}}',
           allRoles = JSON.parse('{!!json_encode($rolesAll) !!}'),
           addedRoles = JSON.parse('{!!json_encode(Session::get('addedRoles')) !!}');
        </script>
    <script src="{{asset('js/useradd_init.min.js')}}"></script>

    {{Session::forget('addedRoles')}}
    @stop
@stop
