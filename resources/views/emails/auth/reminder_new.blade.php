<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/assets/img/palazzin_title.png" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h3>{{trans('reset.title')}}</h3>
   		    <p>
            {{trans('reset.texts.new_pass_user', array(
		    'email' => $user->email,
		    'name' => $user->user_first_name,
		    'user_name' => $user->user_login_name,
            'site' => '<a href="' . URL::to('password/reset', array($token)) . '">' . URL::to('password/reset', array($token)) . '</a>',
		    'url' => URL::to('password/reset', array($token)),
		    'exp' => Config::get('auth.reminder.expire_all') /60,
		    'time' => 'Stunden'
		))}}</p>

		</div>
	</body>
</html>
