<?php

namespace App\Http\Controllers;

use Comment;
use Post;
use Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Response;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 11.10.14
 * Time: 22:35
 */

class CommentController extends Controller
{

    /**
     * Gets all comments from "Show more comments"
     *
     * @return array json
     */
    public function getMoreComments()
    {
        $comments = new Comment();

        return ['comments' => $comments->getCommentsByPostId(Input::get('id')), 'auth' => Auth::id()];
    }

    /**
     * Gets a comment by its id
     *
     * @return mixed Model
     */
    public function getCommentById()
    {
        return Comment::find(Input::get('id'));
    }

    /**
     * Adds a new or saves a comment to a given post
     *
     * @return array json
     */
    public function addComment()
    {
        date_default_timezone_set(trans('formats.tz'));
        $set = Setting::getStaticSettings();

        if (Comment::where('post_id', '=', Input::get('post_id'))->count() >= $set->setting_num_comments) {
            return Response::json(['error' => trans('news.warnings.too_much_comments', ['no' => $set->setting_num_comments])]);
        }
        $credentials = [
            'post_id' => Input::get('post_id'),
            'comment_text' => Input::get('comment_text')
        ];
        $rules = [
            'comment_text' => 'required'
        ];
        $validator = Validator::make(
            $credentials,
            $rules
        );
        if ($validator->fails()) {
            return Response::json(['error' => $validator->messages()->first()]);
        }
        if (Input::has('comment_id')) {
            $comment = Comment::find(Input::get('comment_id'));
        } else {
            $comment = new Comment();
        }
        $comment->addComment($credentials['comment_text'], $credentials['post_id']);
        //$comment->created_at_show = \Carbon\Carbon::createFromFormat('Y-m-d H:m:s', $comment->created_at)->formatLocalized(trans('formats.short-date-time'));
        $comments = new Comment();

        return ['comments' => $comments->getCommentsByPostId(Input::get('post_id')), 'auth' => Auth::id()];
    }

    /**
     * Destroys a comment
     *
     * @return mixed
     */
    public function deleteComment()
    {
        $comment = Comment::destroy(Input::get('id'));
        $posts = new Post();
        return Redirect::back()
            ->with('posts', $posts->getNewsTicker());
    }
}
