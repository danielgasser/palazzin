<div class="container">
    <div class="error-container">

        <h1 style="text-align: center">Oops...<br>{{$error_title ?? ''}}<br>
        </h1>
        <div id="subtitle">.</div>

        <div id="content">
            <h3 style="text-align: center">{{ $exception->getStatusCode() }}: {{  trans('errors.error_text.' . $exception->getStatusCode())  }}</h3>
        </div>
        <div>
            <h2 style="text-align: center"><a href="{{URL::previous()}}">{{trans('navigation.back')}}<br><span class="glyphicon glyphicon-hand-left" style="font-size: 3em;" aria-hidden="true"></span></a></h2>
        </div>
    </div>
</div>

