<!DOCTYPE html>
<html lang="{!!App::getLocale()!!}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
   		    <h3>Error Infos</h3>
            <ul>
            <li>{!!$error!!}</li>
			<li>{!!$url!!}</li>
			<li>{!!$line!!}</li>
			<li>{!!$url_where!!}</li>
			<li>{!!$user_agent!!}</li>
            </ul>
		</div>
	</body>
</html>
