@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{!!asset('assets/js/v3')!!}/DataTables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="{!!asset('assets/css')!!}/datatables_roomapp.css"/>

    <script type="text/javascript" src="{!!asset('assets/js/v3')!!}/DataTables/datatables.min.js"></script>
@stop
@section('content')
    <div class="row">
        {!!Form::open(array('url' => 'userlist', 'class' => 'form-inline', 'style' => 'margin-bottom: 2em', 'role'=> 'form', 'method' => 'post'))!!}
        <div class="col-sm-3 col-md-3">
            {!!Form::label('search_user', "Volltextsuche")!!}<br>
            {!!Form::text('search_user', Input::old('search_user'), array('class' => 'form-control', 'id' => 'search_user', 'placeholder' => trans('admin.user.etc')))!!}
            <br>{!!trans('errors.data')!!}: <span id="records_no">{!!sizeof($allUsers)!!}</span> {!!trans('errors.total_data')!!} {!!sizeof($allUsers)!!}
        </div>
        {{-- clan --}}
        <div class="col-sm-3 col-md-3">
            {!!Form::label('clan_search', trans('userdata.clan'))!!}<br>
            {!!Form::select('clan_search', $clans, Input::old('clan_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-3 col-md-3">
            {!!Form::label('family_search', trans('userdata.halfclan'))!!}<br>
            {!!Form::select('family_search', $families, Input::old('family_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-3 col-md-3">
            {!!Form::label('role_search', trans('userdata.roles'))!!}<br>
            {!!Form::select('role_search', $roleList, Input::old('role_search'), array('class' => 'form-control'))!!}
        </div>
        <div class="col-sm-12 col-md-12">
            <a href="{!!URL::to(Request::url())!!}" class="btn btn-default">{!!trans('dialog.all')!!}</a>
            <a href="{!!URL::to('userlist/print')!!}" class="btn btn-default">{!!trans('dialog.choice')!!} {!!trans('dialog.print')!!}</a>

            <div id="sendMessage" class="btn btn-default">{!!trans('message.send_message')!!}</div>
        </div>
        {!!Form::close()!!}
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @include('message.new_message')
        </div>
    </div>
    <div id="printer">
        <table id="users">
            <thead>
                <tr>
                    <!--th>Alle<input type="checkbox" checked id="send_print_all"></th-->
                    <th style="border-right: none"></th>
                    @if(User::isManager() || User::isLoggedAdmin())
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
                    <th id="more"></th>
                </tr>
            </thead>
            <tbody id="table-body">
                @foreach($allUsers as $user)
                    <tr>
                        <!--td id="send_print_{!!$user->id!!}"><input class="sendPrint" type="checkbox" checked value="{!!$user->email!!}"></td-->
                        <th><a href="{!!URL::to('user/profile') . '/' . $user->id!!}"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a></th>
                        @if(User::isManager() || User::isLoggedAdmin())
                            <th>
                                <a href="{!!URL::to('admin/users/edit') . '/' . $user->id!!}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            </th>
                            <th>
                                <a id="destroyUser_{!!$user->id!!}_{!!$user->user_first_name!!}_{!!$user->user_name!!}" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                            </th>
                            <th>
                                @if($user->user_new == 0) <i class="far fa-registered"></i> @else neu @endif
                            </th>
                        @endif
                        <td>{!!$user->clans['clan_description']!!}/<br>{!!$user->families['family_description']!!}</td>
                         <td class="firstname_{!!$user->id!!}">{!!$user->user_first_name!!}</td>
                         <td class="name_{!!$user->id!!}">{!!$user->user_name!!}</td>
                         <td>{!!$user->user_login_name!!}</td>
                         <td>
                            <ul class="mailz">
                                <li>
                                    <a class="mail_one" href="mailto:{!!$user->email!!}">{!!$user->email!!}</a>
                                </li>
                                    @if(!empty($user->user_email2))
                                <li>
                                    <a href="mailto:{!!$user->user_email2!!}">{!!$user->user_email2!!}</a>
                                </li>
                                    @endif
                            </ul>
                         </td>
                         <td>@if(isset($user->user_www) && !empty($user->user_www))<a href="https://{!!$user->user_www!!}" target="_blank">{!!$user->user_www_label!!}</a>@else Keine Website @endif</td>
                         <td>{!!$user->user_address ?? ''!!}</td>
                         <td>{!!$user->user_zip ?? ''!!}</td>
                         <td>{!!$user->user_city ?? ''!!}</td>
                         <td>{!!$user->country->country ?? ''!!}</td>
                         <td>
                             <ul class="fonz">
                                @if(!empty($user->user_fon1))
                                    <li>{!!trans('userdata.fonlabel.' . $user->user_fon1_label)!!}{!! ($user->user_fon1_label == 'x') ? '' : ': ' !!}{!!$user->user_fon1!!}</li>
                                @endif
                                @if(!empty($user->user_fon2))
                                    <li>{!!trans('userdata.fonlabel.' . $user->user_fon2_label)!!}{!! ($user->user_fon2_label == 'x') ? '' : ': ' !!}{!!$user->user_fon2!!}</li>
                                @endif
                                @if(!empty($user->user_fon3))
                                    <li>{!!trans('userdata.fonlabel.' . $user->user_fon3_label)!!}{!! ($user->user_fon3_label == 'x') ? '' : ': ' !!}{!!$user->user_fon3!!}</li>
                                @endif
                            </ul>
                         </td>
                        <td class="date-header">@if($user->user_birthday == '0000-00-00 00:00:00'){!!'-'!!}@else{!!$user->user_birthday!!}@endif</td>
                         <td class="date-header">{!!$user->user_last_login!!}</td>
                         <td>
                            <ul class="rolez">
                                 @foreach($user->roles as $key => $roles)
                                <li>{!!trans('roles.' . $roles->role_code)!!}</li>
                                 @endforeach
                            </ul>

                         </td>
                        <td class="more"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<div id="debug"></div>
    @include('logged.dialog.user_delete')
    @include('logged.dialog.messagesent')
    @include('logged.dialog.fourchar')
    @section('scripts')
    @parent
    <script src="{!!asset('assets/js/inits/userlist_init.js')!!}"></script>
        <script>
            var ss = 'ASC',
                    a = '#allReservations',
                    locale = '{!!Lang::get('formats.langlangjs')!!}',
                    langDialog = '{!!json_encode(Lang::get('dialog'))!!}',
                    langUser = JSON.parse('{!!json_encode(array_merge(Lang::get('userdata'), Lang::get('profile')))!!}'),
                    langRole = JSON.parse('{!!json_encode(Lang::get('roles'))!!}'),
                    cols = $('th'),
                    yl = [],
                    families = JSON.parse('{!!json_encode($families)!!}'),
                    ml = [],
                    baseUrl = '{!! URL::to('/') !!}',
            isManager = ('{!! User::isManager() || User::isLoggedAdmin() !!}' === '1'),
            userTable;
        </script>
    <script src="{!!asset('assets/js/inits/search_user_tables_init.js')!!}"></script>
    <script>
        $(document).ready( function () {
            if (userTable !== null) {
                $('#users').DataTable().destroy();
            }
            userTable = $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/userlist_search',
                    dataSrc: '',
                    type: 'POST',
                    data: {
                        search_user: $('#search_user').val(),
                        clan_search: $('#clan_search').val(),
                        family_search: $('#family_search').val(),
                        role_search: $('#role_search').val()                    }
                },
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columnDefs: [
                    {
                        className: 'control',
                        orderable: false,
                        targets:   -1,
                        defaultContent: ''
                    }
                ],
                columns: [
                    {
                        data: null
                    },
                    {
                        data: null
                    },
                    {
                        data: null
                    },
                    {
                        data: 'user_new'
                    },
                    {
                        data: 'clan_id'
                    },
                    {
                        data: 'user_first_name'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'user_login_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'user_www'
                    },
                    {
                        data: 'user_address'
                    },
                    {
                        data: 'user_zip'
                    },
                    {
                        data: 'user_city'
                    },
                    {
                        data: 'user_country_code'
                    },
                    {
                        data: 'user_fon1'
                    },
                    {
                        data: 'user_birthday'
                    },
                    {
                        data: 'user_last_login'
                    },
                    {
                        data: 'roles'
                    },
                ],
                order: [
                    3,
                    'asc'
                ],
                searching: false,
                language: {
                    paginate: {
                        first: '{!!trans('pagination.first')!!}',
                        previous: '{!!trans('pagination.previous')!!}',
                        next: '{!!trans('pagination.next')!!}',
                        last: '{!!trans('pagination.last')!!}'
                    },
                    info: '{!!trans('pagination.info')!!}',
                    sLengthMenu: '{!!trans('pagination.length_menu')!!}'
                },
                footerCallback: function (row, data, start, end, display) {
                }
            });
        });
        </script>
    @stop

@stop
