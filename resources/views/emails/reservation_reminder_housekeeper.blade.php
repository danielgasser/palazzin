<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <img src="{{$settings['setting_site_url']}}/assets/img/palazzin_title.png" alt="{{$settings['setting_app_owner']}}" title="{{$settings['setting_app_owner']}}"><br>
   		    <h4>{{trans('address.hello_official', ['d' => trans('address.mr')])}} {{$address_h}}</h4>
   		    <h4>{{trans('reservation.begin_res_housekeeper', ['z' => sizeof($data)])}}</h4>
            @foreach($data as $d)
            <p><strong>{{trans('userdata.user_name')}}:</strong>
                <br>{{$d['address']}}
			</p>
            <p><strong>{{trans('profile.fons_one')}}:</strong>
                <br>{{$d['fon']}}
			</p>
            <p><strong>{{trans('userdata.email')}}:</strong>
                <br><a href="mailto:{{$d['to']}}">{{$d['to']}}</a>
			</p>
   		    <p><strong>{{trans('reservation.arrival')}}:</strong>
                <br>{{$d['from']}}
			</p>
            <p><strong>{{trans('reservation.depart')}}:</strong>
                <br>{{$d['till']}}
            </p>
            <p><strong>Gäste:</strong>
                <br>{{$d['guests']}}
            </p>
                <hr>
            @endforeach
		</div>
	</body>
</html>
