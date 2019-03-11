@extends('layout.master')
@section('header')
    @parent

    <link rel="stylesheet" href="{{asset('libs/bootstrap-datepicker')}}/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" media="screen" type="text/css">

    <link rel="stylesheet" href="{{asset('css')}}/bootstrap-datepicker.min.css"
          rel="stylesheet" media="screen" type="text/css">

    @stop
    @section('content')

    </div>
    {{Form::model($user, array('url' => 'user/profile', 'class' => '', 'id' => 'userProfile', 'files' => true))}}
    {{Form::hidden('id', $user->id)}}
    @if($disabledForm == '' && $user->user_new == 1)
        <fieldset>
            <legend>{{trans('profile.info')}}</legend>
            <div class="row">
                <div class="col-sm-1 col-md-1"></div>
                <div class="col-sm-10 col-md-10{{$requIred[0]}}ired">
                    {{trans('userdata.profile_text')}} <img class="smile" src="{{asset('assets/img/smile_frech.png')}}"
                                                            alt="Zwinker" title="Zwinker">
                </div>
                <div class="col-sm-1 col-md-1"></div>
            </div>
        </fieldset>
    @endif
    <fieldset>
        <legend>{{trans('profile.names')}}</legend>
        <div class="row">
            {{-- name --}}
            {{Form::label('user_name', trans('userdata.user_name'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_name', $user->user_name, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_name'), $disabledForm))}}
            </div>
            {{-- first name --}}
            {{Form::label('user_first_name', trans('userdata.user_first_name'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_first_name', $user->user_first_name, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_first_name'), $disabledForm))}}
            </div>
        </div>
        <div class="row">
            {{-- login name --}}
            {{Form::label('user_login_name', trans('userdata.user_login_name'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_login_name_show', $user->user_login_name, array('class' => 'form-control' . ' ' . trans('userdata.user_login_name'), 'disabled', 'id' => 'user_login_name_show'))}}
                {{Form::hidden('user_login_name', $user->user_login_name, array('class' => 'form-control', $disabledForm))}}
            </div>
            {{-- clan --}}
            {{Form::label('clan_id', trans('userdata.clan'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('clan_id', $clan_desc, array('class' => 'form-control', 'disabled'))}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6">
            </div>
            {{-- family --}}
            {{Form::label('user_family', trans('userdata.halfclan'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_family', $user->family_description, array('class' => 'form-control', 'disabled'))}}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{trans('profile.emails')}}</legend>
        <div class="row">
            {{-- email --}}
            {{Form::label('email', trans('userdata.email'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                @if($disabledForm == '')
                    {{Form::text('email', $user->email, array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.email'), $disabledForm))}}
                @else
                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                @endif
            </div>
            {{-- email2 --}}
            {{Form::label('user_email2', trans('userdata.email2'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                @if($disabledForm == '')
                    {{Form::text('user_email2', (isset($user->user_email2)) ? $user->user_email2 : Input::old('user_email2'), array('class' => 'form-control', $disabledForm))}}
                @else
                    <a href="mailto:{{$user->user_email2}}">{{$user->user_email2}}</a>
                @endif
            </div>
        </div>
        <div class="row">
            {{-- www --}}
            {{Form::label('user_www', trans('profile.www'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                <div class="input-group">
                    @if($disabledForm == '')
                        {{Form::text('user_www', (isset($user->user_www)) ? $user->user_www : Input::old('user_www'), array('class' => 'form-control', $disabledForm))}}
                    @else
                        <a target="_blank" href="http://{{$user->user_www}}">{{$user->user_www}}</a>
                    @endif
                    {{-- ToDo www --}}
                    {{--<span class="input-group-btn">
                        <a href="http://{{$user->user_www}}" target="_blank" class="btn btn-default" type="button">Go!</a>
                    </span>--}}
                </div>
            </div>
            {{-- www label --}}
            {{Form::label('user_www_label', trans('profile.www_label'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_www_label', (isset($user->user_www_label)) ? $user->user_www_label : Input::old('user_www_label'), array('class' => 'form-control', $disabledForm))}}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{trans('profile.fons')}}</legend>
        <div class="row">
            {{-- fon1 label --}}
            {{Form::label('user_fon1_label', trans('userdata.fonalllabel'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                @if($disabledForm == '')
                    {{Form::select('user_fon1_label', [trans('dialog.select')] + Constants::translateFonLabels(), (isset($user->user_fon1_label)) ? $user->user_fon1_label : Input::old('user_fon1_label'), array('class' => 'form-control required inline-input-long' . ' ' . trans('userdata.fon', array('n' => 1)), $disabledForm))}}
                @endif
            </div>
            {{-- fon1 --}}
            {{Form::label('user_fon1', trans('userdata.fon'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                {{Form::text('user_fon1', (isset($user->user_fon1)) ? $user->user_fon1 : Input::old('user_fon1'), array('class' => 'form-control required inline-input-long' . ' ' . trans('userdata.fon', array('n' => 1)), $disabledForm))}}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{trans('profile.address')}}</legend>
        <div class="row">
            {{-- address --}}
            {{Form::label('user_address', trans('userdata.user_address'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-10 col-md-11">
                {{Form::text('user_address', (isset($user->user_address)) ? $user->user_address : Input::old('user_address'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_address'), 'style' => 'width: 99.1%;', $disabledForm))}}
            </div>
        </div>
        <div class="row">
        {{-- zip --}}
            {{Form::label('user_zip', trans('userdata.user_zip') . '/' . trans('userdata.user_city'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-10 col-md-11">
                {{Form::text('user_zip', (isset($user->user_zip)) ? $user->user_zip : Input::old('user_zip'), array('class' => 'form-control input-small ' . $requIred[1] . ' ' . trans('userdata.zip'), $disabledForm))}}
                {{-- city --}}
                {{Form::text('user_city', (isset($user->user_city)) ? $user->user_city : Input::old('user_city'), array('class' => 'form-control input-medium ' . $requIred[1] . ' ' . trans('userdata.city'), $disabledForm))}}
            </div>
        </div>
        <div class="row">
            {{-- country --}}
            {{Form::label('user_country_code', trans('userdata.user_country_code'), array('class' => 'col-sm-2 col-md-1 ' . $requIred[0]))}}
            <div class="col-sm-4 col-md-5">
                {{Form::select('user_country_code', $countries, (isset($user->user_country_code)) ? $user->user_country_code : Input::old('user_country_code'), array('class' => 'form-control ' . $requIred[1] . ' ' . trans('userdata.user_country_code'), $disabledForm))}}
            </div>
            <div class="col-sm-6 col-md-6">
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{trans('profile.pers')}}</legend>
        <div class="row">
            {{-- birthday --}}
            {{Form::label('user_birthday', trans('userdata.birthday'), array('class' => 'col-sm-2 col-md-1'))}}
            <div class="col-sm-3 col-md-3">
                {{Form::input('text', 'user_birthday', (isset($user->user_birthday)) ? $user->user_birthday : Input::old('user_birthday'), array('class' => 'form-control date_type_birthday' . ' ' . trans('userdata.birthday'), $disabledForm, 'readonly' => 'readonly'))}}
            </div>
            {{-- avatar --}}
            {{Form::label('user_avatar', trans('userdata.avatar'), array('class' => 'col-sm-1 col-md-1'))}}
            <div class="col-sm-3 col-md-3">
                @if($disabledForm == '')
                    {{Form::file('user_avatar', array('class' => 'form-control', $disabledForm))}}
                @endif
            </div>
            <div class="col-sm-3 col-md-4">
                <img class="img-responsive" src="{{$user->user_avatar}}"/>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{trans('profile.roles')}}</legend>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>{{trans('roles.role_description', ['n' => ''])}}</th>
                    <th>{{trans('roles.role_tax_annual', array('n' => 'CHF '))}}</th>
                    <th>{{trans('roles.role_tax_night', array('n' => 'CHF '))}}</th>
                    <th>{{trans('roles.role_tax_stock', array('n' => 'CHF '))}}</th>
                    <th>{{trans('rights.right')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user->roles as $key => $roles)
                    <tr>
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
                                @if(sizeof($roles->rights) == 0)
                                    <li>{{trans('rights.norights')}}</li>
                                @else

                                    @foreach($roles->rights as $right)
                                        <li>
                                            {{trans('rights.' . $right->right_code)}}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
    @if($disabledForm == '')

        <fieldset>
            <legend>{{trans('profile.actions')}}</legend>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    {{Form::submit(trans('navigation.profile') . ' ' . trans('dialog.save'), array('class' => 'btn btn-success btn-lg', 'id' => 'saveIt'))}}
                </div>
                <div class="col-sm-6 col-md-6">
                <!-- button type="button" id="profilePrint" class="btn btn-default">{{trans('dialog.print')}}</button -->
                </div>
            </div>
        </fieldset>
    @endif
    {{Form::close()}}
@section('scripts')
    @parent
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="{{asset('libs/bootstrap-datepicker')}}/locales/bootstrap-datepicker.de.min.js"></script>

    <script src="{{asset('js/profile_init.min.js')}}"></script>
@stop
@stop
