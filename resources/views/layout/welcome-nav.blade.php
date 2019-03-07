    <nav id="all-nav-welcome" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-default" id="main-nav-container-welcome">
                <ul id="main-nav-welcome" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if(User::isClerk())
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}">{{trans('navigation.userlist')}}</a></li>
                            <li class="{{ Request::is('admin/bills') ? 'active' : '' }}"><a href="{{URL::to('admin/bills')}}">{{trans('navigation.admin/bills')}}</a></li>
                            <li class="{{ Request::is('admin/bills/filelist') ? 'active' : '' }}"><a href="{{URL::to('admin/bills/filelist')}}">{{trans('navigation.admin/bills/filelist')}}</a>
                            </li>
                        @else
                            <li class="dropdown-toggle" style="border-bottom: 1px solid white;">
                                <a href="#" class="dropdown-toggle dropdownToggleUp" data-toggle="dropdown">{{$userCompleteName}}</a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                                <ul class="dropdown-menu multi-level nav navbar-nav" role="menu">
                                    <li><a style="font-weight: bold">{{trans('navigation.lastlogin')}}:</a></li>
                                    <li><a>{{$lastLogin}}</a></li>
                                    <li class="divider"></li>
                                    <li><a style="font-weight: bold">{{trans('home.yourroles')}}:</a></li>
                                    @foreach($roles as $role)
                                        <li class="{{ Request::is('user/profile') ? 'active' : '' }}"><a>{{ trans('roles.' . $role->role_code) }} ({{ $role->role_code }})</a></li>
                                    @endforeach
                                    <li class="divider"></li>
                                    <li><a style="font-weight: bold">{{trans('home.yourclan')}}:</a></li>
                                    @foreach($clan_name as $clan)
                                        <li style="font-weight: bold">
                                            <a class="nav-clan">
                                                <img style="width: 22px" src="{{asset('assets/img/' . $clan->clan_code . '.png')}}" alt="{{ $clan->clan_description }}" title="{{ $clan->clan_description }}" />
                                                <span class="{{ $clan->clan_code }}-text" style="color: white;">{{ $clan->clan_description }} ({{ $clan->clan_code }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="divider"></li>
                                    <li class="{{ Request::is('user/profile') ? 'active' : '' }}"><a href="{{URL::to('user/profile')}}">{{trans('address.your_m')}} {{trans('navigation.profile')}}</a></li>
                                    @if(!User::isKeeper() && !User::isClerk())
                                        {{--<li class="{{ Request::is('user/reservations') ? 'active' : '' }}"><a href="{{URL::to('user/reservations')}}">{{trans('navigation.user/reservations')}}</a></li>--}}
                                        <li class="{{ Request::is('user/bills') ? 'active' : '' }}"><a href="{{URL::to('user/bills')}}">{{trans('navigation.user/bills')}}</a></li>
                                    @endif
                                    <li class="divider"></li>
                                    <li>
                                        {{ Form::open(array('url' => '/logout')) }}
                                        {{ Form::submit(trans('navigation.logout'), ['class' => 'btn-link-style']) }}
                                        {{ Form::close() }}
                                    </li>
                                </ul>
                            </li>
                            <li class="divider"></li>
                            <li class="{{ Request::is('home') ? 'active' : '' }}"><a href="{{URL::to('home')}}">{{trans('navigation.home')}}</a></li>
                            {{--  <li class="{{ Request::is('calendar') ? 'active' : '' }}"><a href="{{URL::to('calendar')}}">{{trans('navigation.calendar')}}</a>
                             </li> --}}
                             <li class="{{ Request::is('new_reservation') ? 'active' : '' }}"><a href="{{URL::to('new_reservation')}}">{{trans('navigation.new_reservation')}}</a></li>
                             {{-- <li class="{{ Request::is('edit_reservation*') ? 'active' : '' }}"><a href="{{URL::to('edit_reservation')}}">{{trans('navigation.edit_reservation')}}</a></li>                        --}}

                            <li class="{{ Request::is('all_reservations') ? 'active' : '' }}"><a href="{{URL::to('all_reservations')}}">{{trans('navigation.all_reservations')}}</a>
                            </li>
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}">{{trans('navigation.userlist')}}</a></li>
                            <li class="{{ Request::is('pricelist') ? 'active' : '' }}"><a href="{{URL::to('pricelist')}}">{{trans('navigation.pricelist')}}</a>
                            </li>
                            <li class="dropdown-toggle">
                                <a class="dropdown-toggle dropdownToggleUp" data-toggle="dropdown" href="#">{{trans('navigation.admin/stats')}}</a>
                                <ul class="dropdown-menu multi-level nav navbar-nav" role="menu">
                                    <li class="{{ Request::is('stats_chron') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_chron')}}">Gästebuch</a></li>
                                    <li class="{{ Request::is('stats_calendar') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_calendar')}}">Logiernächte</a></li>
                                    <li class="{{ Request::is('stats_bill') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_bill')}}">Jahresabrechnung</a></li>
                                    <li class="{{ Request::is('stats_login') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_login')}}">Logindaten</a></li>
                                    <li class="{{ Request::is('stats_list') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_list')}}">Alle Statistiken als PDF</a></li>
                                </ul>
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

