@extends('layout.master')
@section('content')
        <div class="col-sm-2 col-md-2"></div>
        <div class="col-sm-8 col-md-8">
            <h1>News</h1>
            <h3>Allgemein:</h3>
            <p>Die ganze Website wurde optimiert und die Geschwindigkeit verbessert. Auch wurde das Layout an mobile devices angepasst.</p>
            <h3>Reservation:</h3>
            <p>Die Reservation wurde komlett neu gestaltet und die Geschwindigkeit optimiert. Der Kalender dient nur noch der Übersicht.</p>
                <h3>news 3</h3>
                <h3>news 4</h3>
            <h3>Passwörter:</h3>
            <p>Die Komplexität der Passwörter wurde vereinfacht, da wir Kreditkarten- bzw. PayPal-Zahlungen in absehbarer Zeit nicht integrieren werden</p>
            <p>Auch wurde die Sperre nach 3x falsch anmelden aufgehoben.</p>
        </div>
        <div class="col-sm-2 col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            <div class="welcome-nav">
                <a href="{{URL::to('/')}}">{{trans('navigation.back')}}</a>
            </div>
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
@section('scripts')
    @parent
    <script>
        var oldie = '{{$isOldWin}}';
        $(document).ready(function(){
            if (oldie === '1') {
                $('#old_ie').modal({backdrop: 'static', keyboard: false})
            }
        })
        window.localStorage.clear();
    </script>
@stop
@stop

