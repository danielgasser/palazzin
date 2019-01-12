@section('header')
<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    @if (Request::has('edit_reservation*'))
        <title>{{Lang::get('navigation.edit_reservation')}} | Palazzin</title>
    @else
    <title>{{Lang::get('navigation.' . Route::getFacadeRoot()->current()->uri())}} | Palazzin</title>
    @endif
    <link rel="icon" href="{{asset('assets/img/favicon.png')}}" type="image/png" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="{{asset('assets/js/libs/jquery/jquery.2.1.1.min.js')}}"></script>
    <!--script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script-->
    <!--script src="{{asset('assets/js/libs/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script-->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <link href="{{asset('assets/css/stats_print.css')}}" rel="stylesheet" media="mpdf" type="text/css" />
    <link href="{{asset('assets/css/print.css')}}" rel="stylesheet" media="print" type="text/css" />
    <!--link href="{{asset('assets/css/font-awesome/fontawesome-all.min.css')}}" rel="stylesheet" type="text/css" /-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">


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
    <link href="{{asset('assets/js/libs/chosen/chosen.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('assets/js/libs/chosen/chosen.jquery.min.js')}}"></script>
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
        .chosen-search>input {
            color: #000000 !important;
        }
        .date_type[readonly] {
            cursor: pointer !important;
        }
    </style>

    <script src="{{asset('assets/js/libs/tinymce/js/tinymce/tinymce.min.js')}}"></script>
         <script src="{{asset('assets/js/libs/tinymce/js/tinymce/langs/de.js')}}"></script>
   <script src="{{asset('assets/js/libs/modernizr/modernizr.custom.42303.js')}}"></script>
    @if(Request::is('new_reservation'))
        <link href="{{asset('assets/css/new_reservation.css')}}" rel="stylesheet" type="text/css" />
    @endif
    @if(Request::is('edit_reservation*'))
        <link href="{{asset('assets/css/new_reservation.css')}}" rel="stylesheet" type="text/css" />
    @endif
    @if(Request::is('all_reservations'))
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
    <div id="loading">
       <img id="loadergif" alt="{{trans('dialog.charging')}}" title="{{trans('dialog.charging')}}" src="{{asset('assets/img/preloader.gif')}}">
    </div>
    @section('errors')
        @if ($errors->any())
            <div class="alert alert-danger">
                <button type="button" class="close" style="color: #B7282E" data-dismiss="alert">
                    <span aria-hidden="true">&times</span>
                    <span class="sr-only">Close</span>
                </button>
                <span>{{$errors->first()}}
                </span>
            </div>
        @endif
            @if (Session::has('info_message'))
             <div data-alert class="alert alert-success" role="alert">
                 <button type="button" class="close" style="color: #B7282E" data-dismiss="alert">
                     <span aria-hidden="true">&times</span>
                     <span class="sr-only">Close</span>
                 </button>
                 <h3><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;{{Session::get('info_message')}}!</h3>
             </div>
         @endif
    @show
    @section('navigation')
        @include('layout.nav')
    @show
    @if(strlen(Session::get('message')) > 0)
        <div id="message-wrap">
            <div id="message" class="alert alert-success" role="alert">
                 <button type="button" class="close" data-dismiss="alert">
                 <span aria-hidden="true">&times;</span>
                 <span class="sr-only">Close</span>
                 </button>
                 <ul>
                     <li><h3><span class="glyphicon glyphicon-ok"></span>&nbsp;{{Session::get('message')}}!</h3></li>
                 </ul>
            </div>
        </div>
    @endif
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
    <div class="row">
        <?php
        $router = Route::getFacadeRoot()->current();
        $route = 'error';
        if (!is_null($router)) {
            $route = $router->uri();
        }
        ?>
        @switch($route)
            @case ('/')
                <h1>{{trans('home.welcome', ['back' => '', 'name' => ''])}}</h1>
            @break
            @case ('home')
                <h1>{{trans('home.welcome', array('back' => (User::find(Auth::id())->user_new == 0) ? 'zurück, ' : '',
'name' => User::find(Auth::id())->user_first_name))}}</h1>
            @break
            @case (strpos('stats', $route) !== false)
                <div id="menu_stats">
                    <h1>{{trans('admin.stats_chron.title')}} <span id="stats_title"></span></h1>
                    {{-- @include('layout.stats_menu')--}}
                    <div id="stats_select_menu">
                        @include('layout.stats_select')
                    </div>
                </div>
            @break

                @default
                <h1>{{trans('navigation.' . $route)}}</h1>
            @break
        @endswitch

        @yield('content')
        </div>
    </div>
    @show
</div>
    @section('footer')
        @include('layout.footer')
    @show
   @section('scripts')
    {{--<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js"></script>--}}
    <script src="{{asset('assets/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js')}}"></script>
    <script src="{{asset('assets/js/nav.js')}}"></script>
    <script src="{{asset('assets/js/funcs.js')}}"></script>
    <script src="{{asset('assets/js/funcs_new.js')}}"></script>
    <script src="{{asset('assets/js/browser_check.js')}}"></script>
    <script src="{{asset('assets/js/inits/master_init.js')}}"></script>
     <script>
     var errors_modernizr = '{{trans('errors.modernizr')}}',
            urlTo = '{{URL::to('/')}}',
            otherClanRoleId = $.parseJSON('{!! $otherClanRoleId!!}'),
            urlAssets = '{{asset('')}}',
            settings = JSON.parse({!!json_encode($settingsJSON)!!}),
            bgImg = '{{ asset('assets/') }}' + settings.setting_login_bg_image,
            monthNames = {!!json_encode(Lang::get('calendar.month-names'))!!},
            errors_cookies = '{{trans('errors.cookies')}}',
         route = '{{Route::getFacadeRoot()->current()->uri()}}';
     </script>
    <script>
        window.onerror = function (msg, url, ln) {
            //postErrors([msg, url, ln, location.href]);
            return false;
        };
        jQuery(document).ready(function () {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
        jQuery(document).on('click', '#errors>.close', function () {
            $('#error-wrap').hide();
        });
        jQuery(document).on('click', 'body', function () {
            $('.alert-success').hide();
        });
        jQuery(document).on('mouseout', '#all-nav', function () {
            if (!$(this).is(':hover')) {
                $(this).find('li.open').removeClass('open');
                $(this).removeClass('dropdown-toggle-down');
                $(this).removeClass('dropdown-toggle-up');
                $(this).find('li.dropdown-toggle').children('a').css({
                    'background-color': 'inherit',
                    color: '#f7f7f7'
                })
            }
        });
        jQuery(document).on('click', '.dropdown-toggle', function (e) {
            let classes = e.target.classList;
            if (!classes.contains('hide-footer-nav-text')) {
                $(this).toggleClass('dropdown-toggle-down');
            }
        });
        jQuery(document).on('click', '.dropdownToggleUp', function (e) {
            let classes = e.target.classList;
            if (classes.contains('hide-footer-nav-text')) {
                $(this).removeClass('dropdown-toggle-up');
                $(this).toggleClass('dropdown-toggle-down');
            } else {
                $(this).removeClass('dropdown-toggle-down');
                $(this).toggleClass('dropdown-toggle-up');
            }
        });
        jQuery(document).on('click', '#closeNav', function () {
            $('#all-nav').css({
                width: '55px',
                opacity: '0.75'
            });
            $('#top-nav').css({
                width: '55px',
            });
        });
        jQuery(document).on('click', '#toggleFooterNav', function () {
            $('#bottom-nav').slideToggle(500);
        });
    </script>

   @show

</body>
</html>
