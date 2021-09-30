@extends('layout.master')

@section('content')

</div>
@include('news.post', array('posts' => $posts))

@section('scripts')
    @parent
    <script>
        var lang = $.parseJSON('{!!json_encode((trans('news')))!!}'),
        imgRoute = '{{route('ckeditor.upload', ['_token' => csrf_token() ])}}';
    </script>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="{{asset('js/news_init.min.js')}}"></script>

@stop
@stop
