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
            {{trans('reset.texts.reset_pass', array(
            'site' => '<a href="' . URL::to('password/reset', array($token)) . '">' . URL::to('password/reset', array($token)) . '</a>',
		    'exp' => Config::get('auth.reminder.expire', 2880) / 60,
		    'time' => 'Stunden'
		))}}</p>
		</div>
	</body>
</html>
