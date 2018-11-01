@section('footer')
<div id="footer">
      <div class="container">
            <div class="text-muted" style="color: white;">RoomApp &#169; created by <a target="_blank" href="https://toesslab.ch/"><img title="tösslab - solutions" src="{!!$toesslab!!}">&nbsp;tösslab - solutions</a></div>
          @if($isAdmin == 1 || $isManager == 1)
              <div class="btn-group dropup">

                  <a href="#" onclick="localStorage.clear()">clear</a>
              </div>
          @endif

      </div>
    </div>

@show
