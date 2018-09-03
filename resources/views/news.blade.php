@extends('layout.master')
@section('content')
    <div class="row">
        <div class="col-sm-2 col-md-2"></div>
        <div class="col-sm-8 col-md-8">
            <ul>
                <li>news 1</li>
                <li>news 2</li>
                <li>news 3</li>
                <li>news 4</li>
                <li>news 5</li>
            </ul>
        </div>
        <div class="col-sm-2 col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-4"></div>
        <div class="col-sm-4 col-md-4">
            <div class="welcome-nav">
                <a href="{!!URL::to('/')!!}">{!!trans('navigation.back')!!}</a>
            </div>
        </div>
        <div class="col-sm-4 col-md-4"></div>
    </div>
@section('scripts')
    @parent
    <script>
        var oldie = '{!!$isOldWin!!}';
        $(document).ready(function(){
            if (oldie === '1') {
                $('#old_ie').modal({backdrop: 'static', keyboard: false})
            }
        })
        window.localStorage.clear();
    </script>
@stop
@stop

