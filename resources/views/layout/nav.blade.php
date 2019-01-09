@section('navigation')
    <nav id="all-nav" class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#main-nav-container">
                    <span class="sr-only">{{trans('navigation.togglenav')}}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-brand">
                    <a href="{{URL::to('/')}}">P<span class="hideBrandContent"></span></a>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="main-nav-container">
                <ul id="main-nav" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if(User::isClerk())
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><span class="hideContent">{{trans('navigation.userlist')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills') ? 'active' : '' }}"><a href="{{URL::to('admin/bills')}}"><span class="hideContent">{{trans('navigation.admin/bills')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills/filelist') ? 'active' : '' }}"><a href="{{URL::to('admin/bills/filelist')}}"><span class="hideContent">{{trans('navigation.admin/bills/filelist')}}</span></a>
                            </li>
                        @else
                            <li class="{{ Request::is('home') ? 'active' : '' }}"><a href="{{URL::to('home')}}"><i class="fas fa-home"></i><span class="hideContent">&nbsp;{{trans('navigation.home')}}</span></a></li>
                            <li class="{{ Request::is('calendar') ? 'active' : '' }}"><a href="{{URL::to('calendar')}}"><i class="fas fa-calendar-alt"></i><span class="hideContent">&nbsp;{{trans('navigation.calendar')}}</span></a>
                            </li>
                            <li class="{{ Request::is('new_reservation') ? 'active' : '' }}"><a href="{{URL::to('new_reservation')}}"><i class="fas fa-bed"></i><span class="hideContent">&nbsp;{{trans('navigation.new_reservation')}}</a></li>
                            {{-- <li class="{{ Request::is('edit_reservation*') ? 'active' : '' }}"><a href="{{URL::to('edit_reservation')}}">{{trans('navigation.edit_reservation')}}</a></li>                        --}}

                            <li class="{{ Request::is('all_reservations') ? 'active' : '' }}"><a href="{{URL::to('all_reservations')}}"><i class="fa fa-hotel"></i><span class="hideContent">&nbsp;{{trans('navigation.all_reservations')}}</a>
                            </li>
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><i class="fas fa-users"></i><span class="hideContent">&nbsp;{{trans('navigation.userlist')}}</span></a></li>
                            <li class="{{ Request::is('pricelist') ? 'active' : '' }}"><a href="{{URL::to('pricelist')}}"><i class="fas fa-dollar-sign"></i><span class="hideContent">&nbsp;{{trans('navigation.pricelist')}}</span></a>
                            </li>
                            <li class="dropdown-toggle">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-thermometer-half"></i><span class="hideContent">&nbsp;{{trans('navigation.admin/stats')}}</span></a>
                                <ul class="dropdown-menu multi-level nav navbar-nav" role="menu">
                                    <li class="{{ Request::is('admin/stats_chron') ? 'active' : '' }}"><a href="{{URL::to('admin/stats_chron')}}">Chronologische Anmeldungen
                                            (Gästebuch)</a></li>
                                    <li class="{{ Request::is('admin/stats_calendar') ? 'active' : '' }}"><a href="{{URL::to('admin/stats_calendar')}}">Jahreskalender
                                            (Logiernächte)</a></li>
                                    <li class="{{ Request::is('admin/stats_bill') ? 'active' : '' }}"><a href="{{URL::to('admin/stats_bill')}}">Jahresabrechnung</a></li>
                                    <li class="{{ Request::is('admin/stats_login') ? 'active' : '' }}"><a href="{{URL::to('admin/stats_login')}}">Logindaten</a></li>
                                    <li class="{{ Request::is('admin/stats_list') ? 'active' : '' }}"><a href="{{URL::to('admin/stats_list')}}">Alle Statistiken (PDF)</a></li>
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
        <div class="container-fluid" id="footerNav">
            <div class="collapse navbar-collapse">
                @if(Request::is('new_reservation'))
                    @include('logged.dialog.timeliner')
                    @include('logged.dialog.free_beds_menu')
                @endif
                <ul id="bottom-nav" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if($isAdmin == 1 || $isManager == 1)
                            <li class="dropup" style="float: left">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-cog"></i><span class="hideContent">&nbsp;{{trans('navigation.admin')}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
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
                        <li class="dropup" style="float: left">
                            <a href="#" class="dropup" data-toggle="dropdown"><i class="fas fa-user"></i><span class="hideContent">&nbsp;{{$userCompleteName}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
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
                    @endif
                </ul>
                <ul id="bottom-bottom-nav" class="nav navbar-nav" style="margin: 0; float: right;">
                    <li class="clear-nav-entry{{Request::is('/help/') ? ' active' : '' }}" style="float: right;">
                        <?php
                        $current = \Illuminate\Support\Facades\Route::getFacadeRoot()->current();
                        if (!is_null($current)) {
                            $helper = explode('/', \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri());
                        }
                        ?>
                        @if(Auth::check())
                            <a href="{{URL::to('/help/' . $helper[0])}}"><i class="fas fa-question"></i><span class="hideContent">&nbsp;{{trans('navigation.help')}}</span></a>
                        @else
                                <a href="{{URL::to('/help')}}"><i class="fas fa-question"></i><span class="hideContent">&nbsp;{{trans('navigation.help')}}</span></a>
                        @endif
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <script>
        $(document).on('click', '#logout_user', function (e) {
            e.preventDefault();
            $('#logout-form').submit();
        });
    </script>
@show
