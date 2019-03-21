<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <title>FEHLER | Palazzin</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/favicon')}}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/favicon')}}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/favicon')}}/favicon-16x16.png">
    <link rel="manifest" href="{{asset('img/favicon')}}/site.webmanifest">
    <link rel="mask-icon" href="{{asset('img/favicon')}}/safari-pinned-tab.svg" color="#333333">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="{{asset('css/main.min.css')}}" rel="stylesheet" media="screen" type="text/css" />

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
