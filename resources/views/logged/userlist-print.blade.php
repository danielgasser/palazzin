@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" media="print" href="{{asset('assets/css')}}/datatables_roomapp_print.css"/>

@stop
@section('content')
    </div>
    <div id="printer">
        <table id="users">
            <thead>
                <tr>
                    <th class="0" id="user_first_name">{{trans('userdata.user_name')}}, {{trans('userdata.user_first_name')}}</th>
                    <th class="1" id="user_login_name">{{trans('userdata.user_login_name')}}</th>
                    <th class="2" id="email">{{trans('userdata.email')}}</th>
                    <th class="3" id="user_fon" class="fon-header">{{trans('profile.fons')}}</th>
                    <th class="4" id="user_www_label">{{trans('profile.www_label')}}</th>
                    <th class="5" id="user_address">{{trans('userdata.user_address')}}</th>
                    <th class="6" id="user_zip">{{trans('userdata.user_zip')}}</th>
                    <th class="7" id="user_city">{{trans('userdata.user_city')}}</th>
                    <th class="8" id="user_country_code">{{trans('userdata.user_country_code')}}</th>
                    <th class="9" id="user_birthday" class="date-header">{{trans('userdata.birthday')}}</th>
                    <th class="10" id="clan_id">{{trans('userdata.clan')}}</th>
                    <th class="11" id="family_id">{{trans('userdata.halfclan')}}</th>
                    <th class="12" id="user_role">{{trans('userdata.roles')}}</th>
                    <th class="13" id="user_last_login" class="date-header">{{trans('userdata.user_last_login')}}</th>
                </tr>
            </thead>
            <tbody id="table-body">
            @foreach($allUsers as $u)
                @php
                $birthDay = '';
                if (isset($u->user_birthday)) {
                    $c = explode(' ', $u->user_birthday);
                    $b = explode('-', $c[0]);
                    $birthDay = $b[2] . '.' . $b[1] . ' ' . $b[0];
                }
                @endphp
                    <tr>
                        <td class="0">{{$u->user_first_name}} {{$u->user_name}}</td>
                        <td class="1">{{$u->user_login_name}}</td>
                        <td class="2">{{$u->email}}<br>{{$u->user_email2}}</td>
                        <td class="3" style="white-space: nowrap">{{$u->user_fon1_label}}: {{$u->user_fon1}}<br>{{$u->user_fon2_label}}: {{$u->user_fon2}}<br>{{$u->user_fon3_label}}: {{$u->user_fon3}}</td>
                        <td class="4"><a href="https://{{$u->user_www}}">{{$u->user_www}}</a></td>
                        <td class="5">{{$u->user_address}}</td>
                        <td class="6">{{$u->user_zip}}</td>
                        <td class="7">{{$u->user_city}}</td>
                        <td class="8">{{$countries[$u->user_country_code]}}</td>
                        <td class="9">{{$birthDay}}</td>
                        <td class="10">{{$clans[$u->clan_id]}}</td>
                        <td class="11">{{$families[$u->family_code]}}</td>
                        <td class="12">
                            <ul>
                                @foreach($u->roles as $r)
                                    <li>{{$r->role_description}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="13">{{$u->user_last_login}}</td>
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
        <script>
            $(document).ready(function () {
                window.print();
            })
        </script>
    @stop

@stop
