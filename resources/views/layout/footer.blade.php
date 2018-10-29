@section('footer')
<div id="footer">
      <div class="container">
            <div class="text-muted" style="color: white;">RoomApp &#169; created by <a target="_blank" href="https://toesslab.ch/"><img title="tösslab - solutions" src="{!!$toesslab!!}">&nbsp;tösslab - solutions</a></div>
          @if($isAdmin == 1 || $isManager == 1)
              <div class="btn-group dropup">
                  <button type="button" class="btn btn-small dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{!!trans('navigation.admin')!!}
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                  </button>
                      <ul class="dropdown-menu multi-level navbar-right" role="menu">
                          <li><a tabindex="-1" href="{!!URL::to('admin/users')!!}">{!!trans('navigation.userlist')!!}</a>
                          <li class="divider"></li>
                          <li><a href="{!!URL::to('admin/users/add')!!}">{!!trans('navigation.admin/users/add')!!}</a></li>
                          <li class="divider"></li>
                          <li><a href="{!!URL::to('admin/roles')!!}">{!!trans('navigation.admin/roles')!!}</a></li>
                          <li><a href="{!!URL::to('admin/rights')!!}">{!!trans('navigation.admin/rights')!!}</a></li>
                          <li class="divider"></li>
                          <li><a href="{!!URL::to('admin/reservations')!!}">{!!trans('navigation.admin/reservations')!!}</a></li>
                          <li><a href="{!!URL::to('admin/bills')!!}">{!!trans('navigation.admin/bills')!!}</a></li>
                          <li><a href="{!!URL::to('admin/bills/filelist')!!}">{!!trans('navigation.admin/bills/filelist')!!}</a></li>
                          <li class="divider"></li>
                          <li><a href="{!!URL::to('admin/settings')!!}">{!!trans('navigation.admin/settings')!!}</a></li>
                    </ul>
                  <a href="#" onclick="localStorage.clear()">clear</a>
              </div>
          @endif

      </div>
    </div>

@show
