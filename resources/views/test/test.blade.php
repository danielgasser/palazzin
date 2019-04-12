<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<title>Datepicker Test</title>
    <link href="{{asset('css/main.min.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <link href="{{asset('testit/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" media="screen" type="text/css" />
    <style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
		}

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
</head>
<body>
<div id="wrap">
    <div class="row topRow">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="input-group input-daterange">
                <input type="text" class="form-control" value="2012-04-05">
                <div class="input-group-addon">to</div>
                <input type="text" class="form-control" value="2012-04-19">
            </div>
            <div class="form-group">
                <select class="form-control">
                    <option>foo</option>
                    <option>bar</option>
                    <option>baz</option>
                </select>
            </div>
        </div>
    </div>
    </div>
<script src="{{asset('js/master_init.min.js')}}"></script>

    <script src="{{asset('libs/jquery/jquery.2.1.1.min.js')}}"></script>
    <script src="{{asset('libs/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('testit/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/funcs.min.js')}}"></script>
<script src="{{asset('js/browserNotification.min.js')}}"></script>
<script>
    var         settings = {
        setting_num_bed: 16
    };

</script>
<script src="{{asset('js/funcs.min.js')}}"></script>
<script src="{{asset('js/V3Reservation.min.js')}}"></script>

    <script>
        let today = new Date(),
            datePickerSettings = {
                    format: "dd.mm.yyyy",
                    weekStart: 1,
                    todayBtn: "linked",
                    clearBtn: true,
                    language: 'de',
                    calendarWeeks: true,
                    autoclose: true,
                    todayHighlight: true,
                    startDate: V3Reservation.formatDate(today),
                    endDate: V3Reservation.formatDate(window.endDate),
                    defaultViewDate: {
                        year: today.getFullYear(),
                        month: today.getMonth(),
                        day: today.getDate()
                },
                //orientation: 'auto bottom',
                immediateUpdates: true,
                beforeShowDay: function (Date) {
                   // return V3Reservation.addBeforeShowDayNew(Date, today, false);
                }
            };

        $(document).ready(function () {
        $('.input-daterange').datepicker(datePickerSettings);
    })
</script>
</body>
</html>
