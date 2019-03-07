@section('top_navigation')
    <div class="navbar-default" id="topNav">
        <ul id="bottom-nav" class="nav navbar-nav navbar-right">
            @if(Auth::check())
                @if($isAdmin == 1 || $isManager == 1)
                    <li class="dropdown" style="float: right">
                        <a href="#" class="dropdown-toggle topNav" data-toggle="dropdown"><i class="fas fa-cog"></i><span class="hideContent">&nbsp;{{trans('navigation.admin')}}</span></a>{{--{{User::find(Auth::id())->user_first_name}} {{User::find(Auth::id())->user_name}}--}}
                        <ul class="dropdown-menu nav navbar-nav" role="menu" aria-labelledby="dLabel" id="adminNav">
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
