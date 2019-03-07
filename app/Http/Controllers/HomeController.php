<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showInfos()
    {
        return view('logged.info')
            ->with('locale', $_SERVER['HTTP_ACCEPT_LANGUAGE'])
            ->with('user_agent', $_SERVER['HTTP_USER_AGENT'])
            ->with('get_browser', implode('<br>', get_object_vars(get_browser())));
    }

    public function sendInfos()
    {
        $user = User::find(Auth::id());
        $output = implode('', array_map(
            function ($v, $k) {
                return sprintf("<b>%s</b>: '%s'<br>", $k, $v);
            },
            Input::get(),
            array_keys(Input::get())
        ));
        $data['input'] = $output;
        $data['email'] = $user->email;
        Mail::send('emails.browser_info', $data, function ($message) use ($user) {
            $message->to('software@toesslab.ch')
                ->from($user->email)
                ->subject('palazzin.ch: Browserinfos');
        });

        if (sizeof(Mail::failures()) == 0) {
            return Response::json(['success' => 'Vielen Dank für Deine Hilfe!']);
        }
        return Response::json(['error' => 'E-Mail konnte nicht gesendet werden. Bitte versuche es später noch einmal']);
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
        //$today->setTimestamp(time());
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

    public function saveJSErrors()
    {
        $user = User::find(Auth::id());
        $errors['error'] = Input::get('error');
        $errors['url'] = Input::get('url');
        $errors['line'] = Input::get('line');
        $errors['url_where'] = Input::get('url_where');
        DB::table('errors')->insertGetId(
            [
                'error' => $errors['error'],
                'url' => $errors['url'],
                'line' => $errors['line'],
                'url_where' => $errors['url_where'],
                'user_agent'=> $_SERVER['HTTP_USER_AGENT']
            ]
        );
        Mail::send('emails.error_info', $errors, function ($message) use ($user) {
            $message->to('software@toesslab.ch')
                ->from($user->email)
                ->subject('palazzin.ch: Errorinfos');
        });
    }

    public function getSession()
    {
        return (Session::has('lifetime')) ? '1' : '0';
    }
}
