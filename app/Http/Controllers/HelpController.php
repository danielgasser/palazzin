<?php

namespace App\Http\Controllers;

use Help;
use Illuminate\Support\Facades\Input;
use Auth;
use Response;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 11.10.14
 * Time: 22:35
 */

class HelpController extends Controller
{

    /**
     * Shows the help View
     *
     * @return mixed View
     */
    public function showHelp($topic = null, $error = '')
    {
        if (Auth::guest()) {
            $topic = 'login';
        }
        if ($topic == null && Auth::check()) {
            $topic = 'home';
        } elseif ($topic == null && Auth::guest()) {
            $topic = 'login';
        }
        $routeSelect = [];
        $topics = Help::select('help_topic')->get()->toArray();
        foreach ($topics as $t) {
            $include[] = $t['help_topic'];
        }
        $help = Help::where('help_topic', '=', $topic)->first();
        if (!is_object($help)) {
            $help = Help::where('help_topic', '=', 'none')->first();
        }
        foreach ($include as $val) {
            if (Lang::has('navigation.' . $val)) {
                $routeSelect[$val] = trans('navigation.' . $val);
            } else {
                $this_help = Help::where('help_topic', '=', $val)->first();
                $routeSelect[$val] = $this_help->help_title;
            }
        }
        asort($routeSelect);
        return view('general.help')
            ->with('showUrl', URL::previous())
            ->with('help', $help)
            ->with('helptext', $help->help_text)
            ->with('routes', $routeSelect)
            ->with('error', $error);
    }

    /**
     * Get help data
     *
     * @return string
     */
    public function getDataJson($topic = null)
    {
        $help = new Help();
        $t = ($topic == null) ? Input::get('help_topic') : $topic;
        return Response::json($help->getHelp($t));
    }
}
