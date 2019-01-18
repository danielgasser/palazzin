<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/assets/img/palazzin_title.png" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h3>login info</h3>
   		    <p>
                <pre>
            {{$id}}<br>
            {{$user_login_name}}<br>
            {{$email}}<br>
            </pre>
            </p>

		</div>
	</body>
</html>
