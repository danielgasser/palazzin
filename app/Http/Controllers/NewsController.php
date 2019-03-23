<?php

namespace App\Http\Controllers;

use LoginStat;
use Post;
use User;
use Auth;
use DB;

class NewsController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Default Home Controller
        |--------------------------------------------------------------------------
        |
        | You may wish to use controllers instead of, or in addition to, Closure
        | based routes. That's great! Here is an example controller method to
        | get you started. To route to this controller, just add the route:
        |
        |	Route::get('/', 'HomeController@showWelcome');
        |
        */
    protected $layout = 'layout.master';

    /**
     * Create a new controller instance
     *
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getNews()
    {
        $user = User::find(Auth::id());
        if ($user->isLoggedClerk()) {
            return redirect('userlist');
        }
        $post = new Post();
        $posts = $post->getNewsTicker();
        return view('logged.news')
            ->with('posts', $posts);
    }
}
