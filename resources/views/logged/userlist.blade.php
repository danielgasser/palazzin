@extends('layout.master')
@section('content')
<h1>{!!trans('admin.user.title')!!}</h1>
<div>
    {!!Form::open(array('url' => 'userlist', 'class' => 'form-inline', 'style' => 'margin-bottom: 2em', 'role'=> 'form', 'method' => 'post'))!!}
    <div class="row">
       <div class="col-sm-3 col-md-3">
            {!!Form::text('search_user', Input::old('search_user'), array('class' => 'form-control', 'id' => 'search_user', 'placeholder' => trans('admin.user.etc')))!!}
             <br>{!!trans('errors.data')!!}: <span id="records_no">{!!sizeof($allUsers)!!}</span> {!!trans('errors.total_data')!!} {!!sizeof($allUsers)!!}
       </div>
        {{-- clan --}}
        {!!Form::label('clan_search', trans('userdata.clan'), array('class' => 'col-sm-1 col-md-1'))!!}
        <div class="col-sm-2 col-md-2">
            {!!Form::select('clan_search', $clans, Input::old('clan_search'), array('class' => 'form-control'))!!}
        </div>
        {!!Form::label('family_search', trans('userdata.halfclan'), array('class' => 'col-sm-1 col-md-1'))!!}
        <div class="col-sm-2 col-md-2">
            {!!Form::select('family_search', $families, Input::old('family_search'), array('class' => 'form-control'))!!}
        </div>
        {!!Form::label('role_search', trans('userdata.roles'), array('class' => 'col-sm-1 col-md-1'))!!}
        <div class="col-sm-2 col-md-2">
            {!!Form::select('role_search', $roles, Input::old('role_search'), array('class' => 'form-control'))!!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-5">
            <a href="{!!URL::to(Request::url())!!}" class="btn btn-default">{!!trans('dialog.all')!!}</a>
            <a href="{!!URL::to('userlist/print')!!}" class="btn btn-default">{!!trans('dialog.choice')!!} {!!trans('dialog.print')!!}</a>

            {!!Form::close()!!}
            <div id="sendMessage" class="btn btn-default">{!!trans('message.send_message')!!}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @include('message.new_message')
        </div>
    </div>
    {{--$allUsers->links()--}}
    <div id="printer" class="table-responsive table-myheight">
        <table id="users" class="table tablesorter">
            <thead class="table-head">
                <tr>
                    <!--th>Alle<input type="checkbox" checked id="send_print_all"></th-->
                    <th style="border-right: none"></th>
                    @if(Request::is('admin/users'))
                        <th style="border-right: none; border-left: none">&nbsp;</th>
                        <th style="border-right: none; border-left: none">&nbsp;</th>
                        <th id="user_new">Neu</th>
                    @endif
                    <th id="clan_id">{!!trans('userdata.clan')!!}/<br>{!!trans('userdata.halfclan')!!}</th>
                    <th id="user_first_name">{!!trans('userdata.user_first_name')!!}</th>
                    <th id="user_name">{!!trans('userdata.user_name')!!}</th>
                    <th id="user_login_name">{!!trans('userdata.user_login_name')!!}</th>
                    <th id="email">{!!trans('userdata.email')!!}</th>
                    <th id="user_www_label">{!!trans('profile.www_label')!!}</th>
                    <th id="user_address">{!!trans('userdata.user_address')!!}</th>
                    <th id="user_zip">{!!trans('userdata.user_zip')!!}</th>
                    <th id="user_city">{!!trans('userdata.user_city')!!}</th>
                    <th id="user_country_code">{!!trans('userdata.user_country_code')!!}</th>
                    <th id="user_fon" class="fon-header">{!!trans('profile.fons')!!}</th>
                    <th id="user_birthday" class="date-header">{!!trans('userdata.birthday')!!}</th>
                    <th id="user_last_login" class="date-header">{!!trans('userdata.user_last_login')!!}</th>
                    <th id="role_description">{!!trans('roles.role_description', ['n' => '(n)'])!!}</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach($allUsers as $user)
                    <tr class="tr-body">
                        <!--td id="send_print_{!!$user->id!!}"><input class="sendPrint" type="checkbox" checked value="{!!$user->email!!}"></td-->
                        <td><a href="{!!URL::to('user/profile') . '/' . $user->id!!}"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a></td>
                        @if(Request::is('admin/users'))
                            <td>
                                <a href="{!!URL::to('admin/users/edit') . '/' . $user->id!!}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            </td>
                            <td>
                                <a id="destroyUser_{!!$user->id!!}_{!!$user->user_first_name!!}_{!!$user->user_name!!}" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                            </td>
                            <td>
                                @if($user->user_new == 0) registriert @else neu @endif
                            </td>
                        @endif
                        <td>{!!$user->clans['clan_description']!!}/<br>{!!$user->families['family_description']!!}</td>
                         <td class="firstname_{!!$user->id!!}">{!!$user->user_first_name!!}</td>
                         <td class="name_{!!$user->id!!}">{!!$user->user_name!!}</td>
                         <td>{!!$user->user_login_name!!}</td>
                         <td>
                            <table class="table mailz">
                                <tbody>
                                    <tr>
                                        <td>
                                            <a class="mail_one" href="mailto:{!!$user->email!!}">{!!$user->email!!}</a>
                                        </td>
                                    </tr>
                                    @if(!empty($user->user_email2))
                                    <tr>
                                        <td>
                                            <a href="mailto:{!!$user->user_email2!!}">{!!$user->user_email2!!}</a>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                         </td>
                         <td>@if(isset($user->user_www) && !empty($user->user_www))<a href="http://{!!$user->user_www!!}" target="_blank">{!!$user->user_www_label!!}</a>@else Keine Website @endif</td>
                         <td>{!!$user->user_address or ''!!}</td>
                         <td>{!!$user->user_zip or ''!!}</td>
                         <td>{!!$user->user_city or ''!!}</td>
                         <td>{!!$user->country->country or ''!!}</td>
                         <td>
                             <table class="table fonz">
                                <tbody>
                                @if(!empty($user->user_fon1))
                                    <tr>
                                        <td>{!!trans('userdata.fonlabel.' . $user->user_fon1_label)!!}<br>{!!$user->user_fon1!!}</td>
                                    </tr>
                                @endif
                                @if(!empty($user->user_fon2))
                                    <tr>
                                       <td>{!!trans('userdata.fonlabel.' . $user->user_fon2_label)!!}<br>{!!$user->user_fon2!!}</td>
                                    </tr>
                                @endif
                                @if(!empty($user->user_fon3))
                                    <tr>
                                        <td>{!!trans('userdata.fonlabel.' . $user->user_fon3_label)!!}<br>{!!$user->user_fon3!!}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                         </td>
                        <td class="date-header">@if($user->user_birthday == '0000-00-00 00:00:00'){!!'-'!!}@else{!!$user->user_birthday!!}@endif</td>
                         <td class="date-header">{!!$user->user_last_login!!}</td>
                         <td>
                            <table class="table rolez">
                             <tbody>
                                 @foreach($user->roles as $key => $roles)
                                <tr>
                                     <td>
                                         {!!trans('roles.' . $roles->role_code)!!}
                                     </td>
                                 </tr>
                                 @endforeach
                             </tbody>
                            </table>

                         </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div id="debug"></div>
    @include('logged.dialog.user_delete')
    @include('logged.dialog.messagesent')
    @include('logged.dialog.fourchar')
    @section('scripts')
    @parent
    <script src="{!!asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')!!}"></script>
    <script src="{!!asset('assets/min/js/userlist_init.min.js')!!}"></script>
        <script>
            var ss = 'ASC',
                    a = '#allReservations',
                    locale = '{!!Lang::get('formats.langlangjs')!!}',
                    langDialog = '{!!json_encode(Lang::get('dialog'))!!}',
                    langUser = JSON.parse('{!!json_encode(array_merge(Lang::get('userdata'), Lang::get('profile')))!!}'),
                    langRole = JSON.parse('{!!json_encode(Lang::get('roles'))!!}'),
                    cols = $('th'),
                    yl = [],
                settings = JSON.parse({!!json_encode($settingsJSON)!!}),
                wtf = '',
                    families = JSON.parse('{!!json_encode($families)!!}'),
                    ml = [],
                    route = '{!!Route::getFacadeRoot()->current()->uri()!!}';
        </script>
    <script src="{!!asset('assets/js/inits/search_user_tables_init.js')!!}"></script>
    <script src="{!!asset('assets/min/js/tables.min.js')!!}"></script>
    @stop

@stop
