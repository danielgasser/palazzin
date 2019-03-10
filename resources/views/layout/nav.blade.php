@section('navigation')
    <nav id="all-nav" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul id="top-nav" class="nav navbar-nav navbar-left multi-level">
                    <li><a id="closeNav" href="#"><span class="hideContent">{{trans('dialog.close')}}</span></a></li>
                </ul>
                <div class="navbar-brand">
                    <a href="{{URL::to('/')}}">P<span class="hideBrandContent"></span></a>
                </div>
            </div>
            <div class="navbar-default" id="main-nav-container">
                @if(Auth::check())

                @endif
                <ul id="main-nav" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if(User::isClerk())
                            <li class="dropdown-toggle" style="border-bottom: 1px solid white;">
                                <a href="#" class="dropdown-toggle dropdownToggleUp" data-toggle="dropdown"><i class="fas fa-user"></i><span class="hideContent">&nbsp;{{$userCompleteName}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                                <ul class="dropdown-menu multi-level nav navbar-nav" role="menu">
                                    <li><a style="font-weight: bold">{{trans('navigation.lastlogin')}}:</a></li>
                                    <li><a>{{$lastLogin}}</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        {{ Form::open(array('url' => '/logout')) }}
                                        {{ Form::submit(trans('navigation.logout'), ['class' => 'btn-link-style']) }}
                                        {{ Form::close() }}
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><i class="fas fa-users"></i><span class="hideContent">{{trans('navigation.userlist')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills') ? 'active' : '' }}"><a href="{{URL::to('admin/bills')}}"><i class="fas fa-file-invoice-dollar"></i><span class="hideContent">{{trans('navigation.admin/bills')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills/filelist') ? 'active' : '' }}"><a href="{{URL::to('admin/bills/filelist')}}"><i class="far fa-list-alt"></i><span class="hideContent">{{trans('navigation.admin/bills/filelist')}}</span></a>
                            </li>
                        @else
                            <li class="dropdown-toggle" style="border-bottom: 1px solid white;">
                                <a href="#" class="dropdown-toggle dropdownToggleUp" data-toggle="dropdown"><i class="fas fa-user"></i><span class="hideContent">&nbsp;{{$userCompleteName}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
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
                                                <img style="width: 22px" src="{{asset('img/' . $clan->clan_code . '.png')}}" alt="{{ $clan->clan_description }}" title="{{ $clan->clan_description }}" />
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
                            <li class="{{ Request::is('home') ? 'active' : '' }}"><a href="{{URL::to('home')}}"><i class="fas fa-home"></i><span class="hideContent">&nbsp;{{trans('navigation.home')}}</span></a></li>
                            {{--  <li class="{{ Request::is('calendar') ? 'active' : '' }}"><a href="{{URL::to('calendar')}}"><i class="fas fa-calendar-alt"></i><span class="hideContent">&nbsp;{{trans('navigation.calendar')}}</span></a>
                             </li> --}}
                             <li class="{{ Request::is('new_reservation') ? 'active' : '' }}"><a href="{{URL::to('new_reservation')}}"><i class="fas fa-bed"></i><span class="hideContent">&nbsp;{{trans('navigation.new_reservation')}}</a></li>
                             {{-- <li class="{{ Request::is('edit_reservation*') ? 'active' : '' }}"><a href="{{URL::to('edit_reservation')}}">{{trans('navigation.edit_reservation')}}</a></li>                        --}}

                            <li class="{{ Request::is('all_reservations') ? 'active' : '' }}"><a href="{{URL::to('all_reservations')}}"><i class="fa fa-hotel"></i><span class="hideContent">&nbsp;{{trans('navigation.all_reservations')}}</a>
                            </li>
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><i class="fas fa-users"></i><span class="hideContent">&nbsp;{{trans('navigation.userlist')}}</span></a></li>
                            <li class="{{ Request::is('pricelist') ? 'active' : '' }}"><a href="{{URL::to('pricelist')}}"><i class="fas fa-dollar-sign"></i><span class="hideContent">&nbsp;{{trans('navigation.pricelist')}}</span></a>
                            </li>
                            <li class="dropdown-toggle">
                                <a class="dropdown-toggle dropdownToggleUp" data-toggle="dropdown" href="#"><i class="fas fa-thermometer-half"></i><span class="hideContent">&nbsp;{{trans('navigation.admin/stats')}}</span></a>
                                <ul class="dropdown-menu multi-level nav navbar-nav" role="menu">
                                    <li class="{{ Request::is('stats_chron') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_chron')}}">Gästebuch</a></li>
                                    <li class="{{ Request::is('stats_calendar') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_calendar')}}">Logiernächte</a></li>
                                    <li class="{{ Request::is('stats_bill') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_bill')}}">Jahresabrechnung</a></li>
                                    <li class="{{ Request::is('stats_login') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_login')}}">Logindaten</a></li>
                                </ul>
                            </li>
                        @endif

                    @else
                        <li class="{{ Request::is('navigation./') ? 'active' : '' }}"><a href="/"><i class="far fa-hand-paper"></i><span class="hideContent">&nbsp;{{trans('navigation./')}}</span></a></li>
                        <li class="{{ Request::is('login') ? 'active' : '' }}"><a href="{{URL::to('login')}}"><i class="fas fa-sign-in-alt"></i><span class="hideContent">&nbsp;{{trans('navigation.login')}}</span></a></li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>

@show
