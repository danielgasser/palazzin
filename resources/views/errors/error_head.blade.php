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
                        <li><a href="{{URL::previous()}}"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span><span class="hideContent">&nbsp;{{trans('navigation.back')}}</span></a></li>
                @else
                    <li><a href="/"><span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span><span class="hideContent">&nbsp;{{trans('navigation.back')}}</span></a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
