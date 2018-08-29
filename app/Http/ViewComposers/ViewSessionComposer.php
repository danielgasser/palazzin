<?php
/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 27/08/2018
 * Time: 15:42
 */

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ViewSessionComposer
{
    protected $session;

    public function compose(View $view)
    {
        $this->session = Session::all();
        $view->with('session', $this->session);
    }
}