@section('top_navigation')
    <div class="navbar-default" id="topNav">
        @if(Auth::check())

            <ul id="bottom-user-nav" class="nav navbar-nav navbar-right">
                <li class="dropdown" style="float: left">
                    <a href="#" class="dropdown-toggle topNav" data-toggle="dropdown"><i class="fas fa-user"></i><span class="hideContent">&nbsp;{{$userCompleteName}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                    <ul class="dropdown-menu nav navbar-nav" role="menu" style="min-height: inherit;">
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
                                    <span class="{{ $clan->clan_code }}-text">{{ $clan->clan_description }} ({{ $clan->clan_code }})</span>
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
                        <li><a id="logout_user" href="{{URL::to('logout')}}">{{trans('navigation.logout')}}</a></li>
                    </ul>
                </li>
            </ul>
        @endif
            <ul id="bottom-nav" class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    @if($isAdmin == 1 || $isManager == 1)
                        <li class="dropdown" style="float: right">
                            <a href="#" class="dropdown-toggle topNav" data-toggle="dropdown"><i class="fas fa-cog"></i><span class="hideContent">&nbsp;{{trans('navigation.admin')}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                            <ul class="dropdown-menu nav navbar-nav" role="menu" aria-labelledby="dLabel">
                                <li><a href="{{URL::to('admin/users/add')}}">{{trans('navigation.admin/users/add')}}</a></li>
                                <li class="divider"></li>
                                <li><a href="{{URL::to('admin/roles')}}">{{trans('navigation.admin/roles')}}</a></li>
                                <li><a href="{{URL::to('admin/rights')}}">{{trans('navigation.admin/rights')}}</a></li>
                                <li class="divider"></li>
                                <li><a href="{{URL::to('admin/reservations')}}">{{trans('navigation.admin/reservations')}}</a></li>
                                <li><a href="{{URL::to('admin/bills')}}">{{trans('navigation.admin/bills')}}</a></li>
                                <li><a href="{{URL::to('admin/bills/filelist')}}">{{trans('navigation.admin/bills/filelist')}}</a></li>
                                <li class="divider"></li>
                                <li><a href="{{URL::to('admin/settings')}}">{{trans('navigation.admin/settings')}}</a></li>
                            </ul>
                        </li>
                    @endif
                @endif
            </ul>

    </div>
@show
