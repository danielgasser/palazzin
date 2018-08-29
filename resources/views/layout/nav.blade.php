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
                    <li><a href="{!!URL::to('userlist')!!}">{!!trans('navigation.userlist')!!}</a></li>
                    <li><a href="{!!URL::to('admin/bills')!!}">{!!trans('navigation.admin/bills')!!}</a></li>
                    <li><a href="{!!URL::to('admin/bills/filelist')!!}">{!!trans('navigation.admin/bills/filelist')!!}</a></li>
                    @else
                <li class="active"><a href="{!!URL::to('home')!!}">{!!trans('navigation.home')!!}</a></li>
                <li><a href="{!!URL::to('reservation')!!}">{!!trans('navigation.reservation')!!}</a></li>
                <li><a href="{!!URL::to('new_reservation')!!}">BETA {!!trans('navigation.new_reservation')!!}</a></li>
                        @if (Request::is('reservation'))
                            <li id="open-legend" class="dropdown-toggle">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{!!trans('dialog.legend')!!}<span class="caret"></span></a>
                                <ul class="dropdown-menu multi-level" role="menu">
                                    <li>
                                        <img style="width: 22px" src="{!!asset('assets/img/WO.png')!!}" alt="Wolf" title="Wolf" />
                                        <span class="WO-text">WO - Wolf</span>
                                    </li>
                                    <li>
                                        <img style="width: 22px" src="{!!asset('assets/img/GU.png')!!}" title="Guggenbühl" alt="Guggenbühl" />
                                        <span class="GU-text">GU - Guggenbühl</span>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li><a href="{!!URL::to('userlist')!!}">{!!trans('navigation.userlist')!!}</a></li>
                        <li><a href="{!!URL::to('pricelist')!!}">{!!trans('navigation.pricelist')!!}</a></li>
                        <li id="open-legend" class="dropdown-toggle">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">{!!trans('navigation.admin/stats')!!}<span class="caret"></span></a>
                            <ul class="dropdown-menu multi-level" role="menu">
                                <li><a href="{!!URL::to('admin/stats_chron')!!}">Chronologische Anmeldungen (Gästebuch)</a></li>
                                <li><a href="{!!URL::to('admin/stats_calendar')!!}">Jahreskalender (Logiernächte)</a></li>
                                <li><a href="{!!URL::to('admin/stats_bill')!!}">Jahresabrechnung</a></li>
                                <li><a href="{!!URL::to('admin/stats_login')!!}">Logindaten</a></li>
                                <li><a href="{!!URL::to('admin/stats_list')!!}">Alle Statistiken (PDF)</a></li>
                            </ul>
                            </li>
                @endif

            </ul>
                <ul class="nav navbar-nav navbar-right">
                <li class="dropdown-toggle">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{!!User::find(Auth::id())->user_first_name!!} {!!User::find(Auth::id())->user_name!!}<span class="caret"></span></a>
                    <ul class="dropdown-menu list-inline" role="menu">
                       <li><a href="{!!URL::to('user/profile')!!}">{!!trans('address.your_m')!!} {!!trans('navigation.profile')!!}</a></li>
                       @if(!User::isKeeper() && !User::isClerk())
                        <li><a href="{!!URL::to('user/reservations')!!}">{!!trans('navigation.user/reservations')!!}</a></li>
                       <li><a href="{!!URL::to('user/bills')!!}">{!!trans('navigation.user/bills')!!}</a></li>
                       @endif
                        <li class="divider"></li>
                        <li><a href="{!!URL::to('logout')!!}">Logout</a></li>
                    </ul>
                </li>
                {{--<li class="dropdown">
                                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">{!!trans('navigation.lang')!!}<span class="caret"></span></a>
                                           <ul class="dropdown-menu" role="menu">
                                               <li class="{!!($language == 'de') ? 'lang_active' : ''!!}"><a href="{!!URL::to('lang/de')!!}">{!!trans('navigation.de')!!}</a></li>
                                               <li class="{!!($language == 'en') ? 'lang_active' : ''!!}"><a href="{!!URL::to('lang/en')!!}">{!!trans('navigation.en')!!}</a></li>
                                           </ul>
                                       </li>--}}
            @else
                    <li class="active"><a href="/">{!!trans('navigation.login')!!}</a></li>
            @endif
                    <li class="clear-nav-entry">
                        <?php
                        $helper = explode('/', Route::getFacadeRoot()->current()->uri());
                        ?>
                         @if(Auth::check())
                            <a href="{!!URL::to('/help/' . $helper[0])!!}">{!!trans('navigation.help')!!}</a></li>
                        @else
                        <a href="{!!URL::to('/help')!!}">{!!trans('navigation.help')!!}</a></li>
                        @endif
            </ul>
        </div>
    </div>
</nav>
@show
