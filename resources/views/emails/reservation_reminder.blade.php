<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{!!$settings['setting_site_url']!!}/{!!$settings['setting_app_logo']!!}" alt="{!!$settings['setting_app_owner']!!}" title="{!!$settings['setting_app_owner']!!}"><br>
   		    <h4>{!!trans('address.hello')!!} {!!$res['address']!!}</h4>
   		    <h4>{!!$res['message_text']!!}</h4>
   		    <p>{!!trans('reservation.arrival')!!}:
                <br>{!!$res['from']!!}
			</p>
            <p>{!!trans('reservation.depart')!!}:
                <br>{!!$res['till']!!}
            </p>
		</div>
	</body>
</html>
