<?php

namespace App\Http\Controllers;

use LoginStat;
use Post;
use User;
use Auth;
use DB;

class HomeController extends Controller
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
    public function getHome()
    {
        $user = User::find(Auth::id());
        if ($user->isLoggedClerk()) {
            return redirect('userlist');
        }
        date_default_timezone_set(trans('formats.tz'));
        $today = new \DateTime();
        $r = $today->format('Y-m-d H:m:s');

        $t = \Carbon\Carbon::createFromFormat('Y-m-d H:m:s', $r)->formatLocalized(trans('formats.short-date-time'));
        $login = LoginStat::where('user_id', '=', Auth::id())->orderBy('created_at', 'DESC')->skip(1)->first();
        $post = new Post();
        $posts = $post->getNewsTicker();
        $wasNotHere = DB::table('password_reminders')->select('email')->where('email', '=', $user->email)->count();
        return view('logged.home')
            ->with('roles', $user->getRoles())
            ->with('clan', $user->getUserClan())
            ->with('clan_name', $user->getUserClanName($user->clan_id))
            ->with('wasNeverHereBefore', $wasNotHere)
            ->with('posts', $posts)
            ->with('lastLogin', (!is_null($login)) ? $login->created_at : '')
            ->with('today', $t);
    }
}
