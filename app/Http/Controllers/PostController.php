<?php

namespace App\Http\Controllers;

use Post;
use Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
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
        $credentials = [
            'user_id' => Auth::id(),
            'post_text' => Input::get('post_text')
        ];
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
        Post::destroy(Input::get('id'));
        return [];
    }
}
