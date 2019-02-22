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
                <ul id="main-nav" class="nav navbar-nav navbar-left multi-level">
                    @if(Auth::check())
                        @if(User::isClerk())
                            <li class="{{ Request::is('userlist') ? 'active' : '' }}"><a href="{{URL::to('userlist')}}"><span class="hideContent">{{trans('navigation.userlist')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills') ? 'active' : '' }}"><a href="{{URL::to('admin/bills')}}"><span class="hideContent">{{trans('navigation.admin/bills')}}</span></a></li>
                            <li class="{{ Request::is('admin/bills/filelist') ? 'active' : '' }}"><a href="{{URL::to('admin/bills/filelist')}}"><span class="hideContent">{{trans('navigation.admin/bills/filelist')}}</span></a>
                            </li>
                        @else
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
                                    <li class="{{ Request::is('stats_list') ? 'active' : '' }}"><a class="sub-menu" href="{{URL::to('stats_list')}}">Alle Statistiken als PDF</a></li>
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
        <div class="container-fluid">
            <div class="navbar-default" id="footerNav">
                <ul id="bottom-bottom-nav" class="nav navbar-nav" style="margin: 0; float: right;">
                    <li class="clear-nav-entry{{Request::is('/help/') ? ' active' : '' }}" style="float: right;">
                        <?php
                        $current = \Illuminate\Support\Facades\Route::getFacadeRoot()->current();
                        if (!is_null($current)) {
                            $helper = explode('/', \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri());
                        }
                        ?>
                        <a href="{{URL::to('/help/' . $helper[0])}}"><i class="fas fa-question"></i><span class="hideContent">&nbsp;{{trans('navigation.help')}}</span></a>
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
