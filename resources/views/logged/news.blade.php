@extends('layout.master')

@section('content')

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
