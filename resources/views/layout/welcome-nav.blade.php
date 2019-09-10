    <nav id="all-nav-welcome" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-default" id="main-nav-container-welcome">
                <ul id="main-nav-welcome" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if(User::isClerk())
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><i class="fas fa-users"></i>&nbsp;{{trans('navigation.userlist')}}</a></li>
                            <li class="{{ Request::is('admin/bills') ? 'active' : '' }}"><a href="{{URL::to('admin/bills')}}">{{trans('navigation.admin/bills')}}</a></li>
                            <li class="{{ Request::is('admin/bills/filelist') ? 'active' : '' }}"><a href="{{URL::to('admin/bills/filelist')}}">{{trans('navigation.admin/bills/filelist')}}</a>
                            </li>
                        @else
                            <li id="readIt"><a style="z-index: 1000; border: 2px solid #b7282e !important; background-color: white !important; color: #b7282e !important;" target="_blank" href="{{URL::to('/files/___checklist/Checkliste_Benutzer_Palazzin.pdf')}}"><div>WICHTIG! Bitte lesen!</div><div>Benutzer-Checkliste</div></a></li>
                            <li class="{{ Request::is('news') ? 'active' : '' }}"><a href="{{URL::to('news')}}"><i class="fas fa-newspaper"></i>&nbsp;{{trans('navigation.news')}}</a></li>
                            <li class="{{ Request::is('user/profile') ? 'active' : '' }}"><a href="{{URL::to('user/profile', [Auth::id()])}}"><i class="fas fa-user"></i> {{trans('address.my_m')}} {{trans('navigation.profile')}}</a></li>
                             <li class="{{ Request::is('new_reservation') ? 'active' : '' }}"><a href="{{URL::to('new_reservation')}}"><i class="fas fa-bed"></i>&nbsp;{{trans('navigation.new_reservation')}}</a></li>
                            <li class="{{ Request::is('all_reservations') ? 'active' : '' }}"><a href="{{URL::to('all_reservations')}}"><i class="fa fa-hotel"></i>&nbsp;{{trans('navigation.all_reservations')}}</a>
                            </li>
                            <li class="{{ Request::is('reservations') ? 'active' : '' }}"><a href="{{URL::to('reservations')}}"><i class="fa fa-hotel"></i>&nbsp;{{trans('navigation.reservations')}}</a>
                            </li>
                            <li class="{{ Request::is('user/bills') ? 'active' : '' }}"><a href="{{URL::to('user/bills')}}"><i class="fa fa-credit-card" aria-hidden="true"></i> {{trans('navigation.user/bills')}}</a></li>
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><i class="fas fa-users"></i>&nbsp;{{trans('navigation.userlist')}}</a></li>
                            <li class="{{ Request::is('pricelist') ? 'active' : '' }}"><a href="{{URL::to('pricelist')}}"><i class="fas fa-dollar-sign"></i>&nbsp;{{trans('navigation.pricelist')}}</a></li>
                            <li class="{{ Request::is('pricelist') ? 'active' : '' }}"><a href="{{URL::to('stats')}}"><i class="fas fa-thermometer-half"></i>&nbsp;{{trans('navigation.admin/stats')}}</a></li>
                            <li class=""><a href="https://xn--tsslab-wxa.ch/kurse/palazzin" target="_blank"><i class="fa fa-question" aria-hidden="true"></i>&nbsp;Anleitungen</a>
                            </li>

                        @endif
                    @else
                        <li class="{{ Request::is('navigation./') ? 'active' : '' }}"><a href="/">{{trans('navigation./')}}</a></li>
                        <li class="{{ Request::is('login') ? 'active' : '' }}"><a href="{{URL::to('login')}}">{{trans('navigation.login')}}</a></li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>

