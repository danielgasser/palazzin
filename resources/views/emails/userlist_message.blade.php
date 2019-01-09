<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/{{$settings['setting_app_logo']}}" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h4>{{trans('message.title')}} {{$from}}</h4>
   		    <p>
			<p>{{$message_text}}</p>
			</p>
		</div>
	</body>
</html>
