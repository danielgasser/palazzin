@section('navigation')
<nav id="main-nav" class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav-container">
                <span class="sr-only">{!!trans('navigation.togglenav')!!}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{!!URL::to('/')!!}">
            <img src="{!!URL::to('/')!!}{!!$settings['setting_app_logo']!!}" title="{!!$settings['setting_app_owner']!!}" />
            </a>
        </div>
        <div class="collapse navbar-collapse" id="main-nav-container">
            <ul id="main-nav" class="nav navbar-nav navbar-left multi-level">
            @if(Auth::check())
                @if(User::isClerk())
                    <li class="{!! Request::is('userlist') ? 'active' : '' !!}"><a href="{!!URL::to('userlist')!!}">{!!trans('navigation.userlist')!!}</a></li>
                    <li class="{!! Request::is('admin/bills') ? 'active' : '' !!}"><a href="{!!URL::to('admin/bills')!!}">{!!trans('navigation.admin/bills')!!}</a></li>
                    <li class="{!! Request::is('admin/bills/filelist') ? 'active' : '' !!}"><a href="{!!URL::to('admin/bills/filelist')!!}">{!!trans('navigation.admin/bills/filelist')!!}</a></li>
                @else
                    <li class="{!! Request::is('home') ? 'active' : '' !!}"><a href="{!!URL::to('home')!!}"><i class="fas fa-home"></i>&nbsp;{!!trans('navigation.home')!!}</a></li>
                    <li class="{!! Request::is('calendar') ? 'active' : '' !!}"><a href="{!!URL::to('calendar')!!}"><i class="fas fa-calendar-alt"></i>&nbsp;{!!trans('navigation.calendar')!!}</a></li>
                    <li class="{!! Request::is('new_reservation') ? 'active' : '' !!}"><a href="{!!URL::to('new_reservation')!!}"><i class="fas fa-bed"></i>&nbsp;{!!trans('navigation.new_reservation')!!}</a></li>
                    {{-- <li class="{!! Request::is('edit_reservation*') ? 'active' : '' !!}"><a href="{!!URL::to('edit_reservation')!!}">{!!trans('navigation.edit_reservation')!!}</a></li>                        --}}

                    <li class="{!! Request::is('all_reservations') ? 'active' : '' !!}"><a href="{!!URL::to('all_reservations')!!}"><i class="fa fa-hotel"></i>&nbsp;{!!trans('navigation.all_reservations')!!}</a></li>
                    <li class="{!! Request::is('userlist') ? 'active' : '' !!}"><a href="{!!URL::to('userlist')!!}"><i class="fas fa-users"></i>&nbsp;{!!trans('navigation.userlist')!!}</a></li>
                    <li class="{!! Request::is('pricelist') ? 'active' : '' !!}"><a href="{!!URL::to('pricelist')!!}"><i class="fas fa-dollar-sign"></i>&nbsp;{!!trans('navigation.pricelist')!!}</a></li>
                    <li id="open-legend" class="dropdown-toggle">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-thermometer-half"></i>&nbsp;{!!trans('navigation.admin/stats')!!}<span class="caret"></span></a>
                        <ul class="dropdown-menu multi-level" role="menu">
                            <li class="{!! Request::is('admin/stats_chron') ? 'active' : '' !!}"><a href="{!!URL::to('admin/stats_chron')!!}">Chronologische Anmeldungen (Gästebuch)</a></li>
                            <li class="{!! Request::is('admin/stats_calendar') ? 'active' : '' !!}"><a href="{!!URL::to('admin/stats_calendar')!!}">Jahreskalender (Logiernächte)</a></li>
                            <li class="{!! Request::is('admin/stats_bill') ? 'active' : '' !!}"><a href="{!!URL::to('admin/stats_bill')!!}">Jahresabrechnung</a></li>
                            <li class="{!! Request::is('admin/stats_login') ? 'active' : '' !!}"><a href="{!!URL::to('admin/stats_login')!!}">Logindaten</a></li>
                            <li class="{!! Request::is('admin/stats_list') ? 'active' : '' !!}"><a href="{!!URL::to('admin/stats_list')!!}">Alle Statistiken (PDF)</a></li>
                        </ul>
                    </li>
                @endif

            @else
                <li class="{!! Request::is('navigation./') ? 'active' : '' !!}"><a href="/">{!!trans('navigation./')!!}</a></li>
                <li class="{!! Request::is('login') ? 'active' : '' !!}"><a href="{!!URL::to('login')!!}">{!!trans('navigation.login')!!}</a></li>
            @endif
            </ul>
        </div>
    </div>
</nav>

<form id="logout-form" action="{!! route('logout') !!}" method="POST" style="display: none;">
    {!! csrf_field() !!}
</form>
    <script>
        $(document).on('click', '#logout_user', function (e) {
            e.preventDefault();
            $('#logout-form').submit();
        });
    </script>
@show
