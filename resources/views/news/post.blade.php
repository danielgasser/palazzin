<div class="col-sm-12 col-md-12 posts-top">
    <h2  style="float: left;">{!!trans('news.title')!!}</h2>
    <h2 class="add_post">
        <button id="add_post" class="btn btn-default">{!!trans('news.new_post')!!}</button>
    </h2>
</div>
@include('news.new_post', array('posts' => $posts))
<div id="newsticker">
    @foreach($posts as $p)
    <div id="post_{!!$p->id!!}">
        <div class="row">
           <div class="col-sm-12 col-md-12 posts">
                <h4>{!!$p->created_at!!}: {!!trans('news.post_from')!!} <a href="{!!URL::to('user/profile')!!}/{!!$p->user_id!!}">{!!$p->user_login_name!!}</a>
                    <span>@if(Auth::id() == $p->uid && $p->editable == '1')<span id="editPost_{!!$p->id!!}" class="glyphicon glyphicon-pencil edit"></span><span id="deletePost_{!!$p->id!!}" class="glyphicon glyphicon-remove edit"></span>@endif</span>
                </h4>
            </div>
        </div>
        <div class="row postrow">
                <div class="col-sm-12 col-md-12 posts">
                    {!!utf8_encode($p->post_text)!!}
                </div>
        </div>
    </div>
    @endforeach
</div>
