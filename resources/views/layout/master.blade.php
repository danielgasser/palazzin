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
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width" />
    @if (strpos('edit_reservation', $routeStr) !== false)
        <title>Palazzin | {{Lang::get('reservation.edit_res')}}</title>
    @else
    <title>Palazzin | {{Lang::get('navigation.' . Route::getFacadeRoot()->current()->uri())}}</title>
    @endif
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicon')}}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon')}}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicon')}}/favicon-16x16.png">
    <link rel="manifest" href="{{asset('img/favicon')}}/site.webmanifest">
    <link rel="mask-icon" href="{{asset('img/favicon')}}/safari-pinned-tab.svg" color="#333333">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <style>
        .alert {
            position: fixed;
            width: 100%;
            z-index: 10000;
            top: 0;
            border-radius: 0;
        }
        html, body {
            overflow-y: scroll; /* has to be scroll, not auto */
            -webkit-overflow-scrolling: touch;
        }
    </style>
@if (Request::is('stats'))
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
    <link href="{{asset('libs/fontawesome-free-5.4.1-web/css/all.min.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <link href="{{asset('libs/DataTables/datatables.min.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <link href="{{asset('css/main.min.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <link href="{{asset('libs/bootstrap-toggle/bootstrap-toggle.css')}}" rel="stylesheet" media="screen" type="text/css" />
@if (Request::is('/') || Request::is('login') || Request::is('stats'))
        <style>
            body {
                background-color: rgba(24,19,12,0.05);
            }
            #wrap {
                position: relative;
            }
            #wrap:after {
                content: "";
                background: url({{asset('img/bg_images/login/6tja0l4xj9.png')}});
                background-repeat: no-repeat;
                background-size: cover;
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
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-39618164-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-39618164-1');
    </script>
<style>
    body.apple-ios.modal-open {
        position: fixed;
        width:100%;
    }
    .datepicker {
        z-index: 2147483647;
    }
</style>
</head>
@show
<body>
<script>
    var isMobile = {
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        }
    }
    if(isMobile.iOS()) {
        document.body.classList.add('apple-ios');
    }
</script>

<div id="hideAll"></div>
    @if (Request::is('reservation'))
 @include('logged.reservation_edit')
    @endif
<div id="wrap">
    <img id="loading" alt="{{trans('dialog.charging')}}" title="{{trans('dialog.charging')}}" src="{{asset('img/preloader.gif')}}">
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
                @php
                $info_message = Session::get('info_message');
                $message = Session::get('message');
                Session::forget(['info_message', 'message'])
                @endphp
                <div class="modal fade in" tabindex="-1" role="dialog">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                             <h4 class="modal-title-info">{!! trans('dialog.info') !!}</h4>
                         </div>
                         <div class="modal-body">
                             <p>{!! $info_message !!} {!! $message !!}</p>
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
    <div class="row topRow">
        <div class="col-md-11 col-sm-11 col-xs-11">
        @switch($route)
            @case ('/')
            <div id="res-nav">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h1>{{trans('home.welcome', ['back' => 'zurück', 'name' => $first_name])}}</h1>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12 navbar-default" style="background-color: transparent">
                    <h5><i class="fa fa-id-badge" aria-hidden="true"></i>&nbsp;{{ trans('home.yourroles') }}</h5>
                            <ul>
                                @foreach($roles as $role)
                                    <li><a>{{ $role->role_code }} - {{ trans('roles.' . $role->role_code) }}</a></li>
                                @endforeach
                            </ul>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 navbar-default" style="background-color: transparent">
                    <h5><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;{{ trans('home.yourclan') }}</h5>
                            <ul>
                                @foreach($clan_name as $clan)
                                    <li><a><span class="{{ $clan->clan_code }}-text">{{ $clan->clan_description }}</span></a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
                @break
            @case ('home')
                <h1>{{trans('home.welcome', array('back' => (User::find(Auth::id())->user_new == 0) ? 'zurück, ' : '',
'name' => User::find(Auth::id())->user_first_name))}}</h1>
            @break
            @case (strpos($route, 'stats_') !== false)
                <div id="menu_stats">
                    @if ($route !== 'stats_list')
                    <h1>{{trans('admin.' . $route . '.title')}} <span id="stats_title"></span></h1>
                    <div id="stats_select_menu">
                        @include('layout.stats_select')
                    </div>
                    @else
                        <h1>{{trans('admin.stats_list.title')}}</h1>
                    @endif
                </div>
            @break
            @case(strpos('new_reservation', $route) !== false || strpos('edit_reservation', $routeStr) !== false)
            <div id="res-nav">
                <div class="col-md-4 col-sm-6 col-xs-12 title-res">
                    <h1>{{trans('navigation.' . $routeStr)}}</h1>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 title-res navbar-default">
                    @if(strpos('edit_reservation', $routeStr) === false)
                        {{-- ToDo--}}
                       {{--  @include('logged.dialog.timeliner')--}}
                    @endif
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 title-res navbar-default">
                    @include('logged.dialog.free_beds_menu')
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12 title-res navbar-default">
                    @if(strpos('edit_reservation', $routeStr) === false)
                        @include('logged.dialog.legend')
                    @endif
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
                        @if (User::isManager() || User::isLoggedAdmin() && $user->id !== Auth::id() && $disabledForm == '')
                            <h1>{{trans('profile.title', array(
             'first_name' => $user->user_first_name,
             'name' => $user->user_name,
             'login_name' => $user->user_login_name,
             'id' => $user->id,
             'files'=>true))}}
                            </h1>

                        @elseif($disabledForm == '')
                    <h1>{{trans('profile.title', array(
            'first_name' => User::find(Auth::id())->user_first_name,
            'name' => User::find(Auth::id())->user_name,
            'login_name' => User::find(Auth::id())->user_login_name,
            'id' => User::find(Auth::id())->id,
            'files'=>true))}}
                    </h1>
                    <h1>{{trans('profile.title', array(
             'first_name' => $user->user_first_name,
             'name' => $user->user_name,
             'login_name' => $user->user_login_name,
             'id' => $user->id,
             'files'=>true))}}
                    </h1>
                @endif
                    </div>
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
        <div class="col-md-1 col-sm-12 col-xs-12">
            @if(strpos('new_reservation', $route) !== false || strpos('edit_reservation', $routeStr) !== false)
            <div id="reservationInfo"></div>
            @endif
            @include('layout.top_nav')
        </div>
    </div>
    <div class="row">
        @yield('content')
        </div>
    </div>
    @show

   @section('scripts')
        <script src="{{asset('libs/jquery/jquery.2.1.1.min.js')}}"></script>
        <script src="{{asset('libs/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{asset('libs/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script>
        <script src="{{asset('libs/DataTables/datatables.min.js')}}"></script>
        <script src="{{asset('js/funcs.min.js')}}"></script>
        <script src="{{asset('js/browserNotification.min.js')}}"></script>
        <script>
            var urlTo = '{{URL::to('/')}}',
                langDialog = {!!json_encode(Lang::get('dialog'))!!},
                paginationLang = $.parseJSON('{!!json_encode((trans('pagination')))!!}'),
                settings = JSON.parse({!!json_encode($settingsJSON)!!}),
                token = '{{ csrf_token() }}',
                autid = '{{Auth::id()}}',
                monthNames = {!!json_encode(Lang::get('calendar.month-names'))!!},
                route = '{{Route::getFacadeRoot()->current()->uri()}}';
        </script>
        @guest
        <script src="{{asset('js/guest_init.min.js')}}"></script>
        @endguest
    @auth
    <script src="{{asset('js/master_init.min.js')}}"></script>
       @endauth
@show
@include('logged.dialog.session')

</body>
</html>
