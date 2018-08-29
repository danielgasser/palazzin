<?php

namespace App\Http\Controllers;

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

class PostController extends Controller
{

    /**
     * All posts
     *
     * @return mixed View
     */
    public function showPost()
    {
        $posts = new Post();
        return view('news.post')
            ->with('posts', $posts->getNewsTicker());
    }

    /**
     * Reload posts on event
     *
     * @return mixed json
     */
    public function reloadPost()
    {
        $posts = new Post();
        return Response::json([$posts->getNewsTicker(), 'auth' => Auth::id()]);
    }

    /**
     * Saves a new or edited post
     *
     * @return mixed json
     */
    public function savePost()
    {
        date_default_timezone_set(trans('formats.tz'));
        $set = Setting::getStaticSettings();
        $credentials = [
            'user_id' => Auth::id(),
            'post_text' => Input::get('post_text')
        ];
        // ToDo First or create
        $post = Post::firstOrCreate(['id' => intval(Input::get('id'))]);
        $rules = [
            'post_text' => 'required'
        ];
        $validator = Validator::make(
            $credentials,
            $rules
        );
        if ($validator->fails()) {
            return Response::json(['error', $validator->messages()->first()]);
        }
        $post->user_id = Auth::id();
        $post->post_text = Input::get('post_text');
        $post->save();
        $posts = new Post();
        // TEST
        if (!Input::has('id')) {
            $mail = [
                'id' => $post->id,
                'user_id' => $post->user_id,
                'post_text' => $post->post_text,
            ];
            Mail::send('emails.post_info', $mail, function ($message) use ($set) {
                $message->to('daenuboehmle@gmail.com', 'Daniel Gasser')
                    ->from($set->setting_app_owner_email, $set->setting_app_owner)
                    ->sender($set->setting_app_owner_email, $set->setting_app_owner)
                    ->subject('Post Info!');
            });
        }

        return Response::json([$posts->getNewsTicker($post->id), 'auth' => Auth::id()]);
    }

    /**
     * Gets a post by its id
     *
     * @return mixed Model
     */
    public function getPostById()
    {
        return Post::find(Input::get('id'));
    }

    /**
     * Destroys a post
     *
     * @return mixed
     */
    public function deletePost()
    {
        $post = Post::destroy(Input::get('id'));
        $posts = new Post();
        return Redirect::back()
            ->with('posts', $posts->getNewsTicker());
    }
}
