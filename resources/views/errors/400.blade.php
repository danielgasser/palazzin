<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>FEHLER | Palazzin</title>
    <link rel="icon" href="{!!asset('assets/img/favicon.png')!!}" type="image/png" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="{!!asset('assets/img/favicon.ico')!!}" />
    <link href="{!!asset('assets/js/libs/tablesorter/themes/black/style.css')!!}" rel="stylesheet" type="text/css" />
    <link href="{!!asset('assets/css/main.css')!!}" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{!!asset('assets/js/libs/modernizr/modernizr.custom.42303.js')!!}"></script>
</head>
@show
<body>
<div id="wrap">
    <nav id="main-nav" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">{!!trans('navigation.togglenav')!!}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{!!URL::to('/')!!}">
                    <img src="{!!$settings['setting_site_url']!!}/{!!$settings['setting_app_logo']!!}" alt="{!!$settings['setting_app_owner']!!}" title="{!!$settings['setting_app_owner']!!}" />
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul id="main-nav" class="nav navbar-nav navbar-left multi-level">

                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div id="noview" class="error-container">

            <h3>{!!$error_title ?? ''!!}<br>{!!trans('errors.subtitle')!!}..
            </h3>
            <div id="subtitle">.</div>

            <div id="content">
                <h2>{!! $exception->getStatusCode() !!}: {!!  trans('errors.error_text.' . $exception->getStatusCode())  !!}</h2>
            </div>
            <div>
                <h2 style="text-align: center"><a href="{!!URL::previous()!!}"><span class="glyphicon glyphicon-hand-left" style="font-size: 3em;" aria-hidden="true"></span></a></h2>
            </div>
        </div>
    </div>
</div>
@section('footer')
    @include('layout.footer')
@show

</body>
</html>
