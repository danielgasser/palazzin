<div class="col-sm-6 col-md-6 col-xs-12 posts-top">
    <button id="add_post" class="btn btn-default">{!!trans('news.new_post')!!}</button>
    @include('news.new_post')
</div>
<div class="col-sm-6 col-md-6 col-xs-12"></div>
<div id="newsticker" class="col-sm-12 col-md-12 col-xs-12">
    @foreach($posts as $p)
        <div id="post_{!!$p->id!!}"><a id="link_post_{!!$p->id!!}"></a>
        <div class="row postrow">
            <div class="col-sm-3 col-md-3 col-xs-12 posts">
                {!!$p->created_at!!}<br><h4><a href="{!!URL::to('user/profile')!!}/{!!$p->user_id!!}">{!!$p->user_first_name!!} {!!$p->user_name!!}</a>
                </h4>
                @if(Auth::id() == $p->uid)
                    <div class="tools">
                        <span id="editPost_{!!$p->id!!}" class="glyphicon glyphicon-pencil edit"></span>
                        <span id="deletePost_{!!$p->id!!}" class="glyphicon glyphicon-remove edit"></span>
                    </div>
                @endif
            </div>
            <div class="col-sm-9 col-md-9 col-xs-12 post-entry">
                {!!utf8_encode($p->post_text)!!}
            </div>
        </div>
    </div>
    @endforeach
</div>
@include('logged.dialog.notify_new_post')
