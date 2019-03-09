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
                        @endif

                    @else
                        <li class="{{ Request::is('navigation./') ? 'active' : '' }}"><a href="/">{{trans('navigation./')}}</a></li>
                        <li class="{{ Request::is('login') ? 'active' : '' }}"><a href="{{URL::to('login')}}">{{trans('navigation.login')}}</a></li>
                    @endif
                </ul>
            </div>

        </div>
    </nav>

