<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>FEHLER@Palazzin</title>
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

            <h1>{!!$error_title ?? ''!!}
            </h1>
            <div id="subtitle">{!!trans('errors.subtitle')!!}...</div>

            <div id="content">
                <h3>
                    <span id="error-code">{!!$error_code ?? ''!!}|</span>
                    <span id="error-status">{!!trans('errors.status')!!}</span>
                    <span id="error-text">{!!$error_text ?? ''!!}</span>
                </h3>
                @if($error_text == '')
                <div class="error">
                  <div class="topics login">
                        <div>
                            <ol>
                            @foreach(trans('help.too-login_text') as $lt)
                                <li>{!!$lt!!}</li>
                            @endforeach
                            </ol>
                        </div>
                   </div>
                </div>
                @endif
            </div>
            <div>
                <h2 style="text-align: center"><a href="{!!URL::previous()!!}"><span class="glyphicon glyphicon-hand-left" style="font-size: 3em;" aria-hidden="true"></span></a></h2>
            </div>
        </div>
    </div>
</div>
<div id="footer">
    <div class="container">
        <div class="text-muted">created by
            <a href="mailto:software@daniel-gasser.com">software@daniel-gasser.com</a>
        </div>
        <div class="text-muted">RoomApp &#169; by Daniel Gasser</div>
    </div>
</div>

</body>
</html>
