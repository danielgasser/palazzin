<?php

namespace App\Http\Controllers;

use Right;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

/**
 * Created by PhpStorm.
 * User: pc-shooter
 * Date: 02.12.14
 * Time: 11:22
 */

class RightController extends Controller
{

    /**
     * Gets all rights
     *
     * @return mixed View
     */
    public function showRights()
    {
        return view('logged.admin.rights')
            ->with('allRights', Right::all());
    }

    /**
     * Finds a right by it's id
     *
     * @param $id input->id Right
     * @return mixed
     */
    public function showEditRight($id)
    {
        $allRights = Right::find($id);
        return view('logged.admin.rightedit')
            ->with('right', $allRights);
    }

    /**
     *
     * ToDo UNSUED METHOD
     *
     * @param $id
     * @return mixed
     */
    public function saveRight($id)
    {
        return Redirect::back();
    }
}
