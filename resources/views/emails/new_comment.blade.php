<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/{{$settings['setting_app_logo']}}" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h4>{{trans('comments.new_comment_available')}}</h4>
   		    <p>
			<p>{{trans('dialog.follow')}} {{trans('comments.this_link')}}</p>
			<a href="https://palazzin.ch/?new_comment={{$new_comment}}&new_comment_user_id={{$new_comment_user_id}}">https://palazzin.ch/?new_comment={{$new_comment}}&new_comment_user_id={{$new_comment_user_id}}</a>
			</p>
		</div>
	</body>
</html>
