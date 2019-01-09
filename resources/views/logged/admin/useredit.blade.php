@extends('layout.master')
@section('content')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <h1>{{trans('profile.title', array(
            'first_name' => $user->user_first_name,
            'name' => $user->user_name,
            'login_name' => $user->user_login_name,
            'id' => $user->id,
            'files'=>true))}}</h1>
            <h3><a href="{{URL::to('admin/users')}}">{{trans('dialog.back', ['to' => 'zu ' . trans('navigation.admin') . ' > ' . trans('navigation.admin/users')])}}</a></h3>
    </div>
</div>
    {{Form::model($user, array('url' => array('admin/users/edit', $user->id), 'class' => '', 'files' => true))}}
        {{Form::hidden('id', $user->id)}}
        <fieldset>
            <legend>{{trans('profile.names')}}</legend>
            <div class="row">
                {{-- name --}}
                {{Form::label('user_name', trans('userdata.user_name'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('user_name', $user->user_name, array('class' => 'form-control' . ' ' . trans('userdata.user_name'), 'disabled'))}}
                </div>
                {{-- first name --}}
                {{Form::label('user_first_name', trans('userdata.user_first_name'), array('class' => 'col-sm-2 col-md-1'))}}
                    <div class="col-sm-4 col-md-5">
                {{Form::text('user_first_name', $user->user_first_name, array('class' => 'form-control' . ' ' . trans('userdata.user_first_name'), 'disabled'))}}
                </div>
            </div>
            <div class="row">
                {{-- login name --}}
                {{Form::label('user_login_name', trans('userdata.user_login_name'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                    {{Form::text('user_login_name_show', $user->user_login_name, array('class' => 'form-control', 'disabled', 'id' => 'user_login_name_show'))}}
                    {{Form::hidden('user_login_name', $user->user_login_name, array('class' => 'form-control'))}}
                </div>
             </div>
             <div class="row">
               {{-- clan --}}
                {{Form::label('clan_id', trans('userdata.clan'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('clan_id', [trans('dialog.select')] + $clans, $user->clan_id, array('class' => 'form-control' . ' ' . trans('userdata.clan')))}}
                </div>
                 {{Form::label('user_family', trans('userdata.halfclan'), array('class' => 'col-sm-2 col-md-1'))}}
                 <div class="col-sm-4 col-md-5">
                     {{Form::select('user_family', [trans('dialog.select')] + $families, $user->family_code, array('class' => 'form-control required' . ' ' . trans('userdata.halfclan')))}}
                     <div class="col-sm-6 col-md-6">
                    <button class="btn btn-default" id="changeClan">{{trans('dialog.change')}}</button>
                 </div>

            </div>
        </fieldset>
        <fieldset>
            <legend>{{trans('navigation.admin/users') . ' ' . trans('dialog.activate')}}</legend>
            <div class="row">
                {{-- active --}}
                {{Form::label('user_active', trans('userdata.user_active'), array('class' => 'col-sm-2 col-md-1'))}}
                <div class="col-sm-4 col-md-5">
                {{Form::select('user_active', [trans('dialog.passive'), trans('dialog.active')], $user->active, array('class' => 'form-control'))}}
                </div>
                 <div class="col-sm-6 col-md-6">
                    <button class="btn btn-default" id="activate">{{trans('dialog.add')}}</button>
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
                    <tbody>
                    @if(sizeof($user->roles) > 0)
                       @foreach($user->roles as $key => $roles)
                        <tr>
                            <td id="deleteRole_{{$roles->id}}_{{$user->id}}">
                                <span class="btn btn-sm glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </td>
                            <td>
                                {{trans('roles.' . $roles->role_code)}}
                            </td>
                            <td>
                                {{$roles->role_tax_annual}}
                            </td>
                            <td>
                                {{$roles->role_tax_night}}
                            </td>
                            <td>
                                {{$roles->role_tax_stock}}
                            </td>
                            <td>
                                <ul>
                                @foreach($roles->rights as $right)
                                    <li>
                                    {{trans('rights.' . $right->right_code)}}
                                    </li>
                                @endforeach
                                </ul>
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
             </div>
             <div class="row">
                {{Form::label('role_id', trans('roles.role_new'), array('class' => 'col-sm-2 col-md-2'))}}
                 <div class="col-sm-4 col-md-4">
                {{Form::select('role_id', $allRoles, trans('dialog.select'), array('class' => 'form-control'))}}
                 </div>
                 <div class="col-sm-6 col-md-6">
                    <button class="btn btn-default" id="edit_add_role">{{trans('dialog.add')}}</button>
                 </div>
              </div>
         </fieldset>
    {{Form::close()}}

    <form>
         <fieldset>
             <legend>{{trans('userdata.email')}} {{trans('remind.user_send_man')}}</legend>
             <div class="row">
                 {{-- active --}}
                 {{Form::label('send', trans('userdata.email'), array('class' => 'col-sm-2 col-md-1'))}}
                 <div class="col-sm-4 col-md-5">
                 {{Form::text('send', $user->email, array('class' => 'form-control', 'disabled'))}}
                 </div>
                  <div class="col-sm-6 col-md-6">
                     <a href="{{URL::to('admin/users/add/sendnew')}}/{{$user->email}}" class="btn btn-default" id="send">{{trans('dialog.send')}}</a>
                  </div>
            </div>
         </fieldset>
</form>
    @include('logged.dialog.role_delete')
    @section('scripts')
    @parent
        <script>
            var user_delete = '{{URL::to('admin/users/edit/delete')}}',
                    route = '{{Route::getFacadeRoot()->current()->uri()}}',
                change_clan = '{{URL::to('admin/users/changeclan')}}',
                user_activate = '{{URL::to('admin/users/activate')}}',
                user_id = '{{$user->id}}',
                    families = {!!json_encode($families)!!},
                    add_role = '{{URL::to('admin/users/addrole')}}';
        </script>
        <script src="{{asset('assets/min/js/admin.min.js')}}"></script>
    @stop
    @section('scripts-end')
    @parent
    @stop
@stop
