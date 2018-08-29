<?php
/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 27/08/2018
 * Time: 15:42
 */

namespace App\Http\ViewComposers;


use Illuminate\View\View;

class ViewSettingsComposer
{
    protected $settings;

    public function compose(View $view)
    {
        $this->settings = \Setting::getStaticSettings();
        $view->with('settings', $this->settings->getSettingsArray()[0]);
        $view->with('settingsJSON', $this->settings->getSettingsJson());
    }
}