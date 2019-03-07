<?php
$router = Route::getFacadeRoot()->current();
$route = 'error';
if (!is_null($router)) {
    $route = $router->uri();
}
$routeStr = preg_replace('/[\[{\(].*[\]}\)]/U' , '', $route);
$routeStr = rtrim($routeStr,"/");
if (strlen($routeStr) === 0) {
    $routeStr = ' ';
}
?>

@section('header')
<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    @if (strpos('edit_reservation', $routeStr) !== false)
        <title>{{Lang::get('reservation.edit_res')}} | Palazzin</title>
    @else
    <title>{{Lang::get('navigation.' . Route::getFacadeRoot()->current()->uri())}} | Palazzin</title>
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/favicon')}}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon')}}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon')}}/favicon-16x16.png">
    <link rel="manifest" href="{{asset('assets/img/favicon')}}/site.webmanifest">
    <link rel="mask-icon" href="{{asset('assets/img/favicon')}}/safari-pinned-tab.svg" color="#333333">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <script src="{{asset('assets/js/libs/jquery/jquery.2.1.1.min.js')}}"></script>
    <!--script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script-->
    <!--script src="{{asset('assets/js/libs/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script-->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link href="{{asset('assets/css/stats_print.css')}}" rel="stylesheet" media="mpdf" type="text/css" />
    <link href="{{asset('assets/css/print.css')}}" rel="stylesheet" media="print" type="text/css" />
    <!--link href="{{asset('assets/css/font-awesome/fontawesome-all.min.css')}}" rel="stylesheet" type="text/css" /-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <style>
        .alert {
            position: fixed;
            width: 100%;
            z-index: 10000;
            top: 0;
            border-radius: 0;
        }

    </style>
@if (Request::is('stats'))
        <link href="{{asset('assets/css/stats.css')}}" rel="stylesheet" media="screen" type="text/css" />
        <style>
            .pagebreak {
                background-color: #18130c !important;}
            [id^="data-total-short_"] .total {
                color: #df7015 !important;
                width: 8%;
            }
            [id^="data-total-short_"] .paid {
                color: #169227 !important;
            }
            [id^="data-total-short_"] .unpaid {
                color: #922825 !important;
            }
            @media print {
                body, html {
                    background-color: #fff;
                }
                #choose_stats, .modal-dialog {
                    display: none;
                }
                #footer  .container .btn-group {
                    display: none;
                }
                table thead tr th, table tbody tr td, .pagebreak {
                    background-color: #ffffff !important;
                    color: #000000;
                    padding: 2px !important;
                }
                table thead tr th[colspan], table tbody tr td[colspan] {
                    border: none !important;
                }
                table {
                    border-collapse: collapse;
                }
                [id^="datatable-year-"] {
                    page-break-after: always;
                }
                .highcharts-container {
                    width: 100% !important;
                    border: none !important;
                }
                [id^="datatable-year-"], #datatable-total {
                    width: 100% !important;
                }
                .total {
                    color: #df7015 !important;
                    width: 8%;
                }
                .paid {
                    color: #169227 !important;
                }
                .unpaid {
                    color: #922825 !important;
                }
                #choose_stats, #stats_select_menu {
                    display: none;
                }
                table {
                    table-layout: auto;
                }

                thead {display: table-header-group;}
                tfoot {display: table-footer-group;}
                tbody {
                    display:table-row-group;
                }
            }
        </style>
    @endif
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @if (Request::is('/') || Request::is('login'))
        <style>
            body {
                background-color: rgba(24,19,12,0.05);
            }
            #wrap {
                position: relative;
            }
            #wrap:after {
                content: "";
                background: url({{asset('assets/img/bg_images/login/6tja0l4xj9.png')}});
                background-repeat: no-repeat;
                background-size: 100% 96%;
                opacity: 0.5;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                position: absolute;
                z-index: -1;
            }
        </style>
    @endif
    <style>
        .date_type[readonly] {
            cursor: pointer !important;
        }
    </style>

   <script src="{{asset('assets/js/libs/modernizr/modernizr.custom.42303.js')}}"></script>
    @if(Request::is('new_reservation') || Request::is('admin/bills'))
        <link href="{{asset('assets/css/new_reservation.css')}}" rel="stylesheet" type="text/css" />
    @endif
    @if(Request::is('edit_reservation*'))
        <link href="{{asset('assets/css/new_reservation.css')}}" rel="stylesheet" type="text/css" />
    @endif
    @if(Request::is('all_reservations'))
        <link href="{{asset('assets/css/new_reservation.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/all_reservation.css')}}" rel="stylesheet" type="text/css" />
    @endif
</head>
@show
<body>
<div id="hideAll"></div>
    @if (Request::is('reservation'))
 @include('logged.reservation_edit')
    @endif
<div id="wrap">
    <img id="loading" alt="{{trans('dialog.charging')}}" title="{{trans('dialog.charging')}}" src="{{asset('assets/img/preloader.gif')}}">
    @section('errors')
        @if ($errors->any())
            <div class="modal fade in" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
                        </div>
                        <div class="modal-body">
                            <p>{!! $errors->first() !!}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">{!!trans('dialog.ok')!!}</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
            @if (Session::has('info_message') || Session::has('message'))
             <div class="modal fade in" tabindex="-1" role="dialog">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                             <h4 class="modal-title-info">{!! trans('dialog.info') !!}</h4>
                         </div>
                         <div class="modal-body">
                             <p>{{Session::get('info_message')}} {{Session::get('message')}}</p>
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-default btn-dialog-left close" data-dismiss="modal" aria-label="Close">{!!trans('dialog.ok')!!}</button>
                         </div>
                     </div>
                 </div>
             </div>
            @endif
    @show
    @section('navigation')
        @include('layout.nav')
    @show
    @section('tests')
      {{--   @include('layout.tests') --}}
    @show
    @section('main')
    <div class="container">
        <div style="display: none">
            <h1 id="testCookie"></h1>
            <h1>    <noscript>{{trans('errors.noscript')}}</noscript>
            </h1>
            <h1 id="canvasCheck"></h1>
        </div>
        @if($isOldWin == '1')
            @include('logged.dialog.old_ie')
        @endif

<div id="noview">
    <div class="row topRow">
        <div class="col-md-6 col-sm-6 col-xs-12">
        @switch($route)
            @case ('/')
                <h1>{{trans('home.welcome', ['back' => '', 'name' => ''])}}</h1>
            @break
            @case ('home')
                <h1>{{trans('home.welcome', array('back' => (User::find(Auth::id())->user_new == 0) ? 'zurück, ' : '',
'name' => User::find(Auth::id())->user_first_name))}}</h1>
            @break
            @case (strpos($route, 'stats') !== false)
                <div id="menu_stats">
                    <h1>{{trans('admin.' . $route . '.title')}} <span id="stats_title"></span></h1>
                    {{-- @include('layout.stats_menu')--}}
                    <div id="stats_select_menu">
                        @include('layout.stats_select')
                    </div>
                </div>
            @break
            @case(strpos('new_reservation', $route) !== false || strpos('edit_reservation', $routeStr) !== false)
            <div id="res-nav">
                <div class="col-md-6 col-sm-6 col-xs-12 title-res">
                    <h1>{{trans('navigation.' . $routeStr)}}</h1>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-6 title-res navbar-default">
                    @include('logged.dialog.timeliner')
                </div>
                <div class="col-md-2 col-sm-2 col-xs-6 title-res navbar-default">
                    @include('logged.dialog.free_beds_menu')
                </div>
            </div>
                @break
                @case (strpos($route, 'bills') !== false)
                    <h1>{{trans('navigation.admin/bills')}}</h1>
                @break
                @case (strpos($route, 'admin/roles/edit') !== false)
                <h1>Rolle bearbeiten: {{$role->role_code}} - {{trans('roles.' . $role->role_code)}}</h1>
                @break
                @case (strpos($route, 'admin/rights/edit') !== false)
                <h1>Rechte bearbeiten: {{$right->right_code}} - {{trans('rights.' . $right->right_code)}}</h1>
                @break
                @case (strpos($route, 'user/profile') !== false)
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if($disabledForm == '')
                    <h1>{{trans('profile.title', array(
            'first_name' => User::find(Auth::id())->user_first_name,
            'name' => User::find(Auth::id())->user_name,
            'login_name' => User::find(Auth::id())->user_login_name,
            'id' => User::find(Auth::id())->id,
            'files'=>true))}}
                    </h1>
                @else
                    <h1>{{trans('profile.title', array(
             'first_name' => $user->user_first_name,
             'name' => $user->user_name,
             'login_name' => $user->user_login_name,
             'id' => $user->id,
             'files'=>true))}}
                    </h1>
                @endif   </div>
                @break
                @case (strpos($route, 'users/edit') !== false)
                    <h1>{{trans('profile.title', array(
            'first_name' => $user->user_first_name,
            'name' => $user->user_name,
            'login_name' => $user->user_login_name,
            'id' => $user->id,
            'files'=>true))}}</h1>
                @break

                @default
                <h1>{{trans('navigation.' . $routeStr)}}</h1>
            @break
        @endswitch
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            @include('layout.top_nav')
        </div>
    </div>
    <div class="row">
        @yield('content')
        </div>
    </div>
    @show
</div>
        @include('logged.dialog.session')
    @section('footer')
        @include('layout.footer')
    @show
   @section('scripts')
    {{--<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js"></script>--}}
    <script src="{{asset('assets/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js')}}"></script>
    <script src="{{asset('assets/js/funcs.js')}}"></script>
    <script src="{{asset('assets/js/inits/master_init.js')}}"></script>
        <script src="{{asset('assets/js/libs/ckeditor/ckeditor.js')}}"></script>
        <script>
            var urlTo = '{{URL::to('/')}}',
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                settings = JSON.parse({!!json_encode($settingsJSON)!!}),
                token = '{{ csrf_token() }}',
                monthNames = {!!json_encode(Lang::get('calendar.month-names'))!!},
                oldie = '{{$isOldWin}}',
                route = '{{Route::getFacadeRoot()->current()->uri()}}';
     </script>
    @show
</body>
</html>
