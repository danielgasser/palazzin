<ul id="free_beds" class="nav navbar-nav navbar-default multi-level" style="margin: 0; float: left;">
    <li class="dropdown-toggle">
        <a data-toggle="dropdown" href="#" id="show-all-free-beds">
            <i class="fas fa-bed"></i><span class="hideContent">&nbsp;{{ trans('reservation.beds_free') }}</span>
        </a>
    </li>
</ul>
<div id="free_beds-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title-info">{!! trans('reservation.beds_free') !!}</h4>
          </div>
          <div class="modal-body">
              <p id="all-free-beds-standard">Alles frei</p>
              <ul id="all-free-beds">
              </ul>
          </div>
      </div>
  </div>
</div>
