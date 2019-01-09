<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/{{$settings['setting_app_logo']}}" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h3>Cron job results</h3>
   		    <p>
                <pre>
            @foreach($pp as $p)
                    {{print_r($p)}}
                    <hr>
            @endforeach
            </pre>
            </p>

		</div>
	</body>
</html>
