<?php

namespace App\Http\Controllers;

use App\Libraries\Tools;
use Setting;
use User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Password;

class xxxRemindersController extends Controller
{

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind()
    {
        return view('password.remind');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind()
    {
        $credentials = ['email' => Input::get('email')];
        $response = Password::remind($credentials, function ($message) {
            $message->subject(trans('reset.title'));
        });
        switch ($response) {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::REMINDER_SENT:
                return redirect('/')->with('info_message', Lang::get($response));
        }
    }

    public function postRemindAll()
    {
        $set = Setting::getStaticSettings();
        $credentials = [
            [
                'email' => 'tester@pc-shooter.ch',
                'user_login_name' => 'letzter.tester'
            ],
        ];
        $credentials = User::select('email', 'user_login_name')->where('user_new', '=', 1)->where('password', '=', '')->get()->toArray();
        Config::set('auth.reminder.email', 'emails.auth.reminder_all');
        Tools::dd($credentials, false);
        foreach ($credentials as $c) {
            Tools::dd($c['email'], false);
            Tools::dd($c['user_login_name'], false);
            $response = Password::remind($c, function ($message, $c) use ($set) {
                $message->subject($set->setting_app_owner . ': ' . trans('reset.title'));
                $message->user = $c;
                $message->all = true;
            });
            switch ($response) {
                case Password::INVALID_USER:
                    Tools::dd(Lang::get($response), false);

                case Password::REMINDER_SENT:
                    Tools::dd(Lang::get($response), false);
            }
        }
        foreach (Mail::failures() as $m) {
            Tools::dd('Failure: '. $m);
        }
    }

    public function postRemindNewUser()
    {
        $set = Setting::getStaticSettings();
        $credentials = ['email' => Input::get('email')];
        Config::set('auth.reminder.email', 'emails.auth.reminder_new');
        $response = Password::remind($credentials, function ($message, $c) use ($set) {
                $message->subject($set->setting_app_owner . ': ' . trans('reset.title'));
                $message->user = $c;
                $message->all = true;
        });
        switch ($response) {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::REMINDER_SENT:
                return redirect('/')->with('info_message', Lang::get($response));
        }
    }

    public function postRemindNewUserManually($email)
    {
        $set = Setting::getStaticSettings();
        // von hand
        $credentials = ['email' => $email];
        Config::set('auth.reminder.email', 'emails.auth.reminder_new');
        $response = Password::remind($credentials, function ($message, $c) use ($set) {
                $message->subject($set->setting_app_owner . ': ' . trans('reset.title'));
                $message->user = $c;
                $message->all = true;
        });
        switch ($response) {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));


            case Password::REMINDER_SENT:
                return Redirect::back()->with('info_message', Lang::get($response));
        }
    }

    public function postRemindFailed()
    {
        $set = Setting::getStaticSettings();
        $hashed = '$2y$10$iSz0agB.tW.UknBAX5wm9.H6/VUBuIRYV.Whf80ioR74F3Vzg64Za';
        $clear = 'GeldGescheit7&';
        Tools::dd($clear, false);
        $password = Hash::make($clear);
        Tools::dd('neues gehasht: ' . $password, true);
        Tools::dd('----' . $password);
        $credentials = [
            [
                'email' => 'lukas_l@gmx.net',
                'user_login_name' => 'lukas.lezzi'
            ],
        ];
        Config::set('auth.reminder.email', 'emails.auth.reminder_all');
        Tools::dd($credentials, false);
        foreach ($credentials as $c) {
            Tools::dd($c['email'], false);
            Tools::dd($c['user_login_name'], false);
            $response = Password::remind($c, function ($message, $c) use ($set) {
                $message->subject($set->setting_app_owner . ': ' . trans('reset.title'));
                $message->user = $c;
                $message->all = true;
            });
            switch ($response) {
                case Password::INVALID_USER:
                    Tools::dd(Lang::get($response), false);

                case Password::REMINDER_SENT:
                    Tools::dd(Lang::get($response), false);
            }
        }
        foreach (Mail::failures() as $m) {
            Tools::dd('Failure: '. $m);
        }
    }
    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            abort(404);
        }

        return view('password.reset')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        Password::validator(function ($credentials) {
            $patternRegChars = '/([0-9@#$?!%A-Za-z])+/';
            $pass = $credentials['password'];
            preg_match($patternRegChars, $pass, $match, PREG_OFFSET_CAPTURE);
            return (sizeof($match) > 0 && strlen($pass) >= 10);
        });
        $rules = [
            'email' => 'email|required',
            'password' => 'required|min:10|confirmed',
            'password_confirmation' => 'required'
        ];
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);

            $user->save();
        });
        switch ($response) {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::PASSWORD_RESET:
                return redirect('logout');
        }
    }
}
