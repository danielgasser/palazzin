@extends('layout.master')

@section('content')

    <div class="col-sm-4 col-md-4">
    <h3 style="color: #dfb20d">WICHTIG! Bitte lesen!<br><a style="z-index: 1000;" target="_blank" href="{{asset('/public/files/___checklist/Checkliste_Benutzer_Palazzin.pdf')}}">Benutzer-Checkliste</a></h3>
    </div>
</div>
@include('news.post', array('posts' => $posts))

@section('scripts')
    @parent
    <script>
        var lang = $.parseJSON('{!!json_encode((trans('news')))!!}');
    </script>
    <script src="{{asset('libs/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('js/news_init.min.js')}}"></script>

@stop
@stop
