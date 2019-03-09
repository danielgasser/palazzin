<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>FEHLER | Palazzin</title>
    <link rel="icon" href="{{asset('assets/img/favicon.png')}}" type="image/png" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
@show
<body>
<div id="wrap">
    @include('errors.error_head')
    @include('errors.error_body')
 </div>>
@section('footer')
    @include('layout.footer')
@show

</body>
</html>
