<ul id="user-res-container" class="nav navbar-nav navbar-default multi-level" style="margin: 0; float: left; background: white">
    <li class="dropdown-toggle" id="user-res-div">
        <a class="dropdown-toggle topNav" data-toggle="dropdown" href="#" id="show-user-res" style="color: #b7282e;">
            <i class="fas fa-clock"></i>&nbsp;{{ trans('reservation.other_titles') }}
        </a>
        <ul class="dropdown-menu multi-level nav navbar-nav" role="menu" id="user-res">
            <li>coming very soon...</li>
            {{--
            @foreach($otherRes as $key => $res)
                <li>{{$key}}
                    <ul style="list-style-type: none">
                        @foreach($res as $k => $r)
                            <li>{!! $r !!}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
            --}}
        </ul>
    </li>
</ul>
