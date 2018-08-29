@section('header')
<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>{!!Lang::get('navigation.' . Route::getFacadeRoot()->current()->uri())!!}@Palazzin</title>
    <link rel="icon" href="{!!asset('assets/img/favicon.png')!!}" type="image/png" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="{!!asset('assets/js/libs/jquery/jquery.2.1.1.min.js')!!}"></script>
    <!--script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script-->
    <script src="{!!asset('assets/js/libs/jquery-ui-1.12.1/jquery-ui.min.js')!!}"></script>
    @if (!Request::is('new_reservation'))
    <script src="{!!asset('assets/js/libs/datepicker_i18n/datepicker-de.js')!!}"></script>
    <script src="{!!asset('assets/js/libs/datepicker_i18n/datepicker-en-GB.js')!!}"></script>
    @endif
    <script src="{!!asset('assets/js/libs/montrezorro-bootstrap-checkbox-fa865ff/js/bootstrap-checkbox.js')!!}"></script>
    <link rel="shortcut icon" href="{!!asset('assets/img/favicon.ico')!!}" />
    <link href="{!!asset('assets/css/main.css')!!}" rel="stylesheet" media="screen" type="text/css" />
    <link href="{!!asset('assets/css/stats_print.css')!!}" rel="stylesheet" media="mpdf" type="text/css" />
    <link href="{!!asset('assets/css/print.css')!!}" rel="stylesheet" media="print" type="text/css" />
    <!--link href="{!!asset('assets/css/font-awesome/fontawesome-all.min.css')!!}" rel="stylesheet" type="text/css" /-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

@if (Request::is('stats'))
        <link href="{!!asset('assets/css/stats.css')!!}" rel="stylesheet" media="screen" type="text/css" />
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
    <link href="{!!asset('assets/js/libs/tablesorter/themes/black/style.css')!!}" rel="stylesheet" type="text/css" />
    <link href="{!!asset('assets/js/libs/montrezorro-bootstrap-checkbox-fa865ff/css/bootstrap-checkbox.css')!!}" rel="stylesheet" type="text/css" />
    <link href="{!!asset('assets/js/libs/chosen/chosen.css')!!}" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{!!asset('assets/js/libs/chosen/chosen.jquery.min.js')!!}"></script>
    @if (Request::is('/') || Request::is('login'))
        <style>
            body {
                background-color: rgba(24,19,12,0.05);
            }
            #wrap {
                background-image: url({!!asset('assets/img/bg_images/login/6tja0l4xj9.png')!!});
                background-repeat: no-repeat;
                background-size: cover;
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
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

    <script src="{!!asset('assets/js/libs/tinymce/js/tinymce/tinymce.min.js')!!}"></script>
         <script src="{!!asset('assets/js/libs/tinymce/js/tinymce/langs/de.js')!!}"></script>
   <script src="{!!asset('assets/js/libs/modernizr/modernizr.custom.42303.js')!!}"></script>
    @if(Request::is('new_reservation'))
        <link href="{!!asset('assets/scss/v3/new_reservation.css')!!}" rel="stylesheet" type="text/css" />
    @endif
</head>
@show
<body>

    @if (Request::is('reservation'))
 @include('logged.reservation_edit')
    @endif
<div id="wrap">
    <div id="loading">
        <div class="row">
            <div class="col-sm-12 col-md-5">&nbsp;
            </div>
            <div class="col-sm-12 col-md-2 ccc">
                {{--trans('dialog.charging') . '...&nbsp;&nbsp;&nbsp;&nbsp;'--}}
               <img id="loadergif" alt="{!!trans('dialog.charging') . '...&nbsp;&nbsp;&nbsp;&nbsp;'!!}" title="{!!trans('dialog.charging') . '...&nbsp;&nbsp;&nbsp;&nbsp;'!!}" src="{!!asset('assets/img/loading.gif')!!}">
            </div>
            <div class="col-sm-12 col-md-5">&nbsp;
            </div>
        </div>
    </div>
    @section('errors')
     @if(sizeof($errors) > 0)
        <div id="error-wrap">
           <div id="errors" class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times</span>
                <span class="sr-only">Close</span>
                </button>
                <ul>
                    <li><h3><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;{!!$errors->first()!!}!</h3></li>
                </ul>
           </div>
        </div>
     @elseif(Session::has('res_error'))
        <div id="error-wrap">
           <div id="errors" class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times</span>
                <span class="sr-only">Close</span>
                </button>
                <ul>
                    <li><h3><span class="glyphicon glyphicon-warning-sign"></span>&nbsp;{!!Session::get('error')!!}!</h3></li>
                </ul>
           </div>
        </div>
         {!!Session::forget('res_error')!!}
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
                     <li><h3><span class="glyphicon glyphicon-ok"></span>&nbsp;{!!Session::get('message')!!}!</h3></li>
                 </ul>
            </div>
        </div>
    @endif
    @section('tests')
      {{--   @include('layout.tests') --}}
    @show
    @section('main')
    <div class="container">
    <h1 id="testCookie"></h1>
    <h1>    <noscript>{!!trans('errors.noscript')!!}</noscript>
    </h1>
    <h1 id="canvasCheck"></h1>
        @if($isOldWin == '1')
            @include('logged.dialog.old_ie')
        @endif

<div id="noview">


        @yield('content')
        </div>
    </div>
    @show
</div>
    @section('footer')
        @include('layout.footer')
    @show
 @include('logged.dialog.login_again')
   @section('scripts')
    {{--<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/minified/i18n/jquery-ui-i18n.min.js"></script>--}}
    <script src="{!!asset('assets/bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.js')!!}"></script>
    <script src="{!!asset('assets/js/funcs.js')!!}"></script>
    <script src="{!!asset('assets/js/funcs_new.js')!!}"></script>
    <script src="{!!asset('assets/js/browser_check.js')!!}"></script>
    <script src="{!!asset('assets/js/inits/master_init.js')!!}"></script>
     <script>
     var errors_modernizr = '{!!trans('errors.modernizr')!!}',
            urlTo = '{!!URL::to('/')!!}',
            otherClanRoleId = $.parseJSON('{!!$otherClanRoleId!!}'),
            urlAssets = '{!!asset('')!!}',
            settings = JSON.parse({!!json_encode($settingsJSON)!!}),
            bgImg = '{!! asset('assets/') !!}' + settings.setting_login_bg_image,
            monthNames = {!!json_encode(Lang::get('calendar.month-names'))!!},
            errors_cookies = '{!!trans('errors.cookies')!!}',
         route = '{!!Route::getFacadeRoot()->current()->uri()!!}';
     old_win = '{!!$isOldWin!!}';
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
            if (old_win === '1') {
                $('#noview-master').remove();
            }
        });

    </script>

   @show
</body>
</html>
