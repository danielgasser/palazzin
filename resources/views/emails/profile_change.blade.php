<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{!!$settings['setting_site_url']!!}/{!!$settings['setting_app_logo']!!}" alt="{!!$settings['setting_app_owner']!!}" title="{!!$settings['setting_app_owner']!!}"><br>
   		    <h3>Profile Change</h3>
   		    <p>
            UserId: {!!$id!!}</p>
            <p>Login: {!!$login!!}</p>
            <p>Old E-Mail: {!!$old_email!!}</p>
            <p>E-Mail: {!!$email!!}</p>

		</div>
	</body>
</html>