@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="card">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('login.login') }}">
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <h1>{{ __('login.login') }}</h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('userdata.user_login_name') . ' ' . __('dialog.or') . ' ' . __('userdata.email') }}</label>

                                <input id="usernameOrEmail" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="usernameOrEmail" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('usernameOrEmail'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('usernameOrEmail') }}</strong>
                                    </span>
                                @endif
                                {{ Form::hidden('new_comment', (isset($_GET['new_comment'])) ? $_GET['new_comment'] : null, array('class' => 'form-control', 'placeholder' => trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email'))) }}
                                {{ Form::hidden('new_comment_user_id', (isset($_GET['new_comment_user_id'])) ? $_GET['new_comment_user_id'] : null, array('class' => 'form-control', 'placeholder' => trans('userdata.user_login_name') . ' ' . trans('dialog.or') . ' ' . trans('userdata.email'))) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('userdata.pass') }}</label>

                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 login-checkbox">
                                <label>
                                    {{ __('login.stay') }}
                                </label>
                                <input type="checkbox" data-onstyle="success" data-offstyle="warning" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <button id="login-btn" type="submit" class="btn btn-primary">
                                    {{ __('login.login') }}
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <hr style="margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #b7282e;"> </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <a class="btn btn-default" style="width: 100%;" href="{{ route('password.request') }}">{{ __('login.forgot') }}?</a>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <a class="btn btn-default" style="width: 100%;" href="{{URL::to('help/pl')}}">{{trans('login.login_prob')}}?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <div class="col-md-4"></div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>
        var oldie = '{{$isOldWin}}';
        $(document).ready(function(){
            if (oldie === '1') {
                $('#old_ie').modal({backdrop: 'static', keyboard: false})
            }
        });
        $(function() {
            $("[name^='remember']").bootstrapToggle({
                on: '<i class=\'fa fa-check\'></i> Ja',
                off: '<i class=\'fa fa-times\'></i> Nein',
            });
        })
        window.localStorage.clear();
    </script>
@stop
