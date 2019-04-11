@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('css')}}/datatables_roomapp.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>
    <style>
        .form-control {
            margin: 0;
        }
        .dataTables_wrapper {
            border: none;
        }
        #datatable-short thead tr th, tbody tr td, #datatable-short-calendar thead tr th, tbody tr td {
            border: none !important;
        }
    </style>

@stop
@section('content')
        {{Form::open(array('url' => 'userlist', 'class' => 'form-inline', 'style' => 'margin-bottom: 2em', 'role'=> 'form', 'method' => 'post'))}}
        <div class="col-sm-3 col-md-4">
            {{Form::label('search_user', "Volltextsuche")}}
            {{Form::text('search_user', Input::old('search_user'), array('class' => 'form-control', 'id' => 'search_user', 'placeholder' => trans('admin.user.etc')))}}
        </div>
        {{-- clan --}}
        <div class="col-sm-3 col-md-2">
            {{Form::label('clan_search', trans('userdata.clan'))}}
            {{Form::select('clan_search', $clans, Input::old('clan_search'), array('class' => 'form-control'))}}
        </div>
        <div class="col-sm-3 col-md-2">
            {{Form::label('family_search', trans('userdata.halfclan'))}}
            {{Form::select('family_search', $families, Input::old('family_search'), array('class' => 'form-control'))}}
        </div>
        <div class="col-sm-3 col-md-2">
            {{Form::label('role_search', trans('userdata.roles'))}}
            {{Form::select('role_search', $roleList, Input::old('role_search'), array('class' => 'form-control'))}}
        </div>
        <div class="col-sm-3 col-md-2">
            <label>&nbsp</label>
            <button id="goSearch" class="btn btn-default">{{trans('dialog.search')}}</button>
        </div>
        {{Form::close()}}
        <div class="col-sm-12 col-md-12">
            <a href="{{URL::to(Request::url())}}" class="btn btn-default">{{trans('dialog.all')}}</a>
            <button id="printChoice" class="btn btn-default">{{trans('dialog.choice')}} {{trans('dialog.print')}} (PDF)</button>
        </div>

        </div>

    <div id="printer">
        <table id="users">
            <thead>
                <tr>
                    <th class="00" id="more"></th>
                    <th class="000" id="moreUID"></th>
                    <th class="0000" id="moreEDIT"></th>
                    <th class="2" id="user_first_name">{{trans('userdata.user_first_name')}}</th>
                    <th class="3" id="user_name">{{trans('userdata.user_name')}}</th>
                    <th class="4" id="user_login_name">{{trans('userdata.user_login_name')}}</th>
                    <th class="5" id="email">{{trans('userdata.email')}}</th>
                    <th class="11" id="user_fon" class="fon-header">{{trans('profile.fons')}}</th>
                    <th class="6" id="user_www_label">{{trans('profile.www_label')}}</th>
                    <th class="7" id="user_address">{{trans('userdata.user_address')}}</th>
                    <th class="8" id="user_zip">{{trans('userdata.user_zip')}}</th>
                    <th class="9" id="user_city">{{trans('userdata.user_city')}}</th>
                    <th class="10" id="user_country_code">{{trans('userdata.user_country_code')}}</th>
                    <th class="12" id="user_birthday" class="date-header">{{trans('userdata.birthday')}}</th>
                    <th class="0" id="clan_id">{{trans('userdata.clan')}}</th>
                    <th class="1" id="family_id">{{trans('userdata.halfclan')}}</th>
                    <th class="14" id="user_role">{{trans('userdata.roles')}}</th>
                    <th class="13" id="user_last_login" class="date-header">{{trans('userdata.user_last_login')}}</th>
                </tr>
            </thead>
            <tbody id="table-body">
                    <tr>
                        <td class="00"></td>
                        <td class="000"></td>
                        <td class="0000"></td>
                        <td class="0"></td>
                        <td class="1"></td>
                        <td class="2"></td>
                        <td class="3"></td>
                        <td class="4"></td>
                        <td class="5"></td>
                        <td class="6"></td>
                        <td class="7"></td>
                        <td class="8"></td>
                        <td class="9"></td>
                        <td class="10"></td>
                        <td class="11"></td>
                        <td class="12"></td>
                        <td class="13"></td>
                        <td class="14"></td>
                    </tr>
            </tbody>
        </table>
    </div>
<div id="debug"></div>
    @include('logged.dialog.user_delete')
    @include('logged.dialog.messagesent')
    @include('logged.dialog.fourchar')
    @include('logged.dialog.print_table_name')
    @section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script>
        var cols = $('th'),
            yl = [],
            families = JSON.parse('{!!json_encode($families)!!}'),
            allUsers = JSON.parse('{!!$allUsers!!}'),
            ml = [],
            isManager = ('{{ User::isManager() || User::isLoggedAdmin() }}' === '1'),
            userListPrintUrl = '{{route('userlist_print')}}';
        </script>
    <script src="{{asset('js/search_user_tables_init.min.js')}}"></script>
    @stop

@stop
