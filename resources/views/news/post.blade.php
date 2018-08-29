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
                <h4>{!!trans('news.post_from')!!} <a href="{!!URL::to('user/profile')!!}/{!!$p->user_id!!}">{!!$p->user_login_name!!}</a></h4>
                <p>{!!$p->created_at!!}@if(Auth::id() == $p->uid && $p->editable == '1')<span id="editPost_{!!$p->id!!}" class="glyphicon glyphicon-pencil edit"></span><span id="deletePost_{!!$p->id!!}" class="glyphicon glyphicon-remove edit"></span>@endif</p>
            </div>
        </div>
        <div class="row postrow">
                <div class="col-sm-12 col-md-12 posts">
                    {!!utf8_encode($p->post_text)!!}
                </div>
            <div class="col-sm-12 col-md-12 comments">
                @if(sizeof($p->comments) > 0)<h4 class="comments-titles">{!!trans('news.comments_title')!!}</h4>@endif
                <ul id="comments_{!!$p->id!!}">
                @foreach($p->comments as $c)
                        <li id="commentId_{!!$c->id!!}">{!!trans('news.comment_from')!!} <a href="{!!URL::to('user/profile')!!}/{!!$c->user_id!!}">{!!$c->user_login_name!!}</a> {!!trans('news.comment_at')!!} {!!$c->created_at!!}
                            @if(Auth::id() == $c->user_id && $c->editable == '1')<span id="editComment_{!!$c->id!!}" class="glyphicon glyphicon-pencil edit"></span><span id="deleteComment_{!!$c->id!!}" class="glyphicon glyphicon-remove edit"></span>@endif
                            <br>"{!!$c->comment_text!!}"</li>
                @endforeach
                </ul>
            </div>
            {{--ToDo Doesn't work with skip() and get() --}}
            @if($p->comment_no > 333)
            <div class="col-sm-12 col-md-12 comments">
                <ul>
                    <li>
                        <a data_no="{!!$p->comment_no!!}" data_less="false" href="#" id="moreComments_{!!$p->id!!}">{!!trans('news.show_')!!} {!!($p->comment_no - sizeof($p->comments))!!} {!!trans('news.show_more_comments')!!}</a>
                    </li>
                </ul>
            </div>
            @endif
            <div class="col-sm-12 col-md-12 comments">
                <a href="#" id="addComment_{!!$p->id!!}">{!!trans('news.add_comment')!!}</a>
            </div>
            <div class="col-sm-12 col-md-4 comments" id="comment-add-area_{!!$p->id!!}">

            </div>
            <div class="col-sm-12 col-md-2 comments" id="comment-add_{!!$p->id!!}">

            </div>
            <div class="col-sm-12 col-md-6 comments">

            </div>
        </div>
    </div>
    @endforeach
</div>
