<?php
/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 27/08/2018
 * Time: 15:50
 */

namespace App\Http\ViewComposers;


use Illuminate\View\View;
use User;

class ViewLoginComposer
{
    protected $userData = [];

    public function compose(View $view)
    {
        $this->userData = [
            'isAdmin' => User::isLoggedAdmin(),
            'isManager' => User::isManager(),
            'isKeeper' => User::isKeeper()
        ];
        $view->with('isAdmin', $this->userData['isAdmin']);
        $view->with('isManager', $this->userData['isManager']);
        $view->with('isKeeper', $this->userData['isKeeper']);
    }

}