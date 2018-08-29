<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{!!$settings['setting_site_url']!!}/{!!$settings['setting_app_logo']!!}" alt="{!!$settings['setting_app_owner']!!}" title="{!!$settings['setting_app_owner']!!}"><br>
   		    <h3>New Post</h3>
   		    <p>
                <pre>
            {!!$id!!}<br>
            {!!$user_id!!}<br>
            {!!$post_text!!}<br>
            </pre>
            </p>

		</div>
	</body>
</html>