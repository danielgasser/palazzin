<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{

    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('ajax-session-expired');

        $this->middleware('auth');
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }

    public function checkSession()
    {
        $this->middleware('ajax-session-expired');

        $this->middleware('auth');
    }
}
