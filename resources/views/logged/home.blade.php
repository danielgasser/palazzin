@extends('layout.master')
@section('content')

    <h3 style="color: #dfb20d">WICHTIG! Bitte lesen!<br><a style="z-index: 1000;" target="_blank" href="{{asset('/public/files/___checklist/Checkliste_Benutzer_Palazzin.pdf')}}">Benutzer-Checkliste</a></h3>
    <div class="col-sm-6 col-md-6">
        <h5>{{trans('home.yourroles')}}:</h5>
        <ul>
          @foreach($roles as $role)
         <li>{{ $role->role_code }} - {{ trans('roles.' . $role->role_code) }}</li>
          @endforeach
        </ul>
    </div>
    <div class="col-sm-6 col-md-6">
        <h5>{{trans('home.yourclan')}}:</h5>
        <ul>
          @foreach($clan_name as $clan)
         <li><span class="{{ $clan->clan_code }}-text">{{ $clan->clan_description }}</span></li>
          @endforeach
         </ul>
     </div>
</div>
<div class="row">
@if(User::find(Auth::id())->user_new == '1')
    <h2><a href="{{URL::to('user/profile')}}">{{trans('home.first_visit')}}</a></h2>
@endif
</div>
@include('news.post', array('posts' => $posts))

@section('scripts')
    @parent
    <script>
        var lang = $.parseJSON('{!!json_encode((trans('news')))!!}'),
            autid = '{{Auth::id()}}';
    </script>
    <script src="{{asset('assets/js/inits/news_init.js')}}"></script>

@stop
@stop
