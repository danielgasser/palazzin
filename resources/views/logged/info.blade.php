@extends('layout.master')
@section('content')
        <div class="col-sm-12 col-md-12">
            <h1>Browserinfos:</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <input type="text" name="locale_server" readonly disabled style="color: #000; width: 50%" cols="25" rows="5" value="{{$locale}}">
        </div>
        <div class="col-sm-3 col-md-3">
            <input type="text" name="locale_js" readonly disabled style="color: #000; width: 50%" cols="25" rows="5" value="{{$locale}}">
        </div>
        <div class="col-sm-3 col-md-3">
            <textarea readonly disabled name="user_agent" style="color: #000;" cols="25" rows="5" >{{$user_agent}}</textarea>
        </div>
        <div class="col-sm-3 col-md-3">
            <textarea readonly disabled name="get_browser" style="color: #000;" cols="25" rows="5" >{{$get_browser}}</textarea>
        </div>
        <div class="col-sm-12 col-md-12">
            <button class="btn btn-primary" name="send_to_webmaster" style="width: 100%">An den Webmaster senden</button>
        </div>
        <div class="col-sm-12 col-md-12">
<h3 id="answer"></h3>
        </div>
    </div>
</div>
@section('scripts')
    @parent
    <script>
        $(document).ready(function(){
            $('[name="locale_js"]').val(navigator.languages);
        });
        $(document).on('click', '[name="send_to_webmaster"]', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/infos',
                data: {
                    locale_server: $('[name="locale_server"]').val(),
                    locale_js: $('[name="locale_js"]').val(),
                    user_agent: $('[name="user_agent"]').val(),
                    get_browser: $('[name="get_browser"]').val()
                },
                success: function (data) {
                    if (data.hasOwnProperty('error')) {
                        $('#answer').css({
                            backgroundColor: '#d53d44',
                            color: '#fff'
                        }).html(data.error);
                    } else {
                        $('#answer').html(data.success);
                    }
                }
            })
        });
    </script>
    @stop
@stop

