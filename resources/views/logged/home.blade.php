@extends('layout.master')
@section('content')

    <h3 style="color: #dfb20d">WICHTIG! Bitte lesen!<br><a style="z-index: 1000;" target="_blank" href="{{asset('/public/files/___checklist/Checkliste_Benutzer_Palazzin.pdf')}}">Benutzer-Checkliste</a></h3>
    <div class="col-sm-4 col-md-4">
        <h3>{{trans('navigation.lastlogin')}}: {{$lastLogin}}</h3>
        <!--h3>Dein Browser:<br><textarea readonly style="color: #000;" cols="25" rows="5" ><?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?></textarea></h3-->
    </div>
    <div class="col-sm-4 col-md-4">
        <h3>{{trans('home.yourroles')}}:</h3>
        <ul>
          @foreach($roles as $role)
         <li>{{ $role->role_code }} - {{ trans('roles.' . $role->role_code) }}</li>
          @endforeach
        </ul>
    </div>
    <div class="col-sm-4 col-md-4">
        <h3>{{trans('home.yourclan')}}:</h3>
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
@include('logged.dialog.comments')
@include('logged.dialog.new_comment')

@section('scripts')
    @parent
    <script>
        var urlTo = '{{URL::to('/')}}',
            urlAssets = '{{asset('')}}',
            lang = $.parseJSON('{!!json_encode((trans('news')))!!}'),
            cc = '{{Comment::all()->count() - 3}}',
            new_comment = '{{Session::get('new_comment')}}',
            new_comment_user_id = '{{Session::get('new_comment_user_id')}}',
            autid = '{{Auth::id()}}';

    </script>
    <script src="{{asset('assets/js/inits/news_init.js')}}"></script>

@stop
@stop
