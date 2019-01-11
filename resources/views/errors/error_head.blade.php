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
                <li><a href="{{URL::previous()}}"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span><span class="hideContent">&nbsp;{{trans('navigation.back')}}</span></a></li>
            </ul>
        </div>
    </div>
</nav>
