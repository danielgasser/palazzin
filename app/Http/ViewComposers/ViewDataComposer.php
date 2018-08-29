<?php
/**
 * Created by PhpStorm.
 * User: toesslab
 * Date: 27/08/2018
 * Time: 15:50
 */

namespace App\Http\ViewComposers;


use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ViewDataComposer
{
    protected $monthColors = [
        '#CD5C5C', '#F08080', '#FA8072', '#E9967A', '#FFA07A', '#DC143C', '#B22222', '#8B0000', '#FFC0CB', '#FFB6C1', '#FF69B4', '#FF1493', '#C71585', '#DB7093', '#FFA07A', '#FF7F50', '#FF6347', '#FF4500', '#FF8C00', '#FFA500', '#FFD700', '#FFFF00', '#FFFFE0', '#FFFACD', '#FAFAD2', '#FFEFD5', '#FFE4B5', '#FFDAB9', '#EEE8AA', '#F0E68C', '#BDB76B', '#E6E6FA', '#D8BFD8', '#DDA0DD', '#EE82EE', '#DA70D6', '#FF00FF', '#FF00FF', '#BA55D3', '#9370DB', '#9966CC', '#8A2BE2', '#9400D3', '#9932CC', '#8B008B', '#800080', '#4B0082', '#6A5ACD', '#483D8B', '#7B68EE', '#ADFF2F', '#7FFF00', '#7CFC00', '#00FF00', '#32CD32', '#98FB98', '#90EE90', '#00FA9A', '#00FF7F', '#3CB371', '#2E8B57', '#228B22', '#008000', '#006400', '#9ACD32', '#6B8E23', '#808000', '#556B2F', '#66CDAA', '#8FBC8F', '#20B2AA', '#008B8B', '#008080', '#00FFFF', '#00FFFF', '#E0FFFF', '#AFEEEE', '#7FFFD4', '#40E0D0', '#48D1CC', '#00CED1', '#5F9EA0', '#4682B4', '#B0C4DE', '#B0E0E6', '#ADD8E6', '#87CEEB', '#87CEFA', '#00BFFF', '#1E90FF', '#6495ED', '#7B68EE', '#4169E1', '#0000FF', '#0000CD', '#00008B', '#000080', '#191970', '#FFF8DC', '#FFEBCD', '#FFE4C4', '#FFDEAD', '#F5DEB3', '#DEB887', '#BC8F8F', '#F4A460', '#DAA520', '#B8860B', '#CD853F', '#D2691E', '#8B4513', '#A0522D', '#A52A2A', '#800000', ];
    protected $yearColors = [
        '#88590f', '#283327', '#FF4500', '#FFD700', '#8A2BE2', '#ffa188', '#1E90FF', '#ffcc00', '#1500ff', '#12aa1c', '#00ff2d'
    ];
    protected $isOldWin;
    protected $toesslab = 'https://toesslab.ch/application/files/5614/5854/7379/favicon.ico';

    public function compose(View $view)
    {
        $this->isOldWin = \User::checkUsersOldWinBrowser();
        shuffle($this->monthColors);
        $view->with('monthColors', $this->monthColors);
        $view->with('yearColors', $this->yearColors);
        $view->with('isOldWin', $this->isOldWin);
        $view->with('toesslab', $this->toesslab);
        $view->with('otherClanRoleId', $this->getOtherClanRoleId());
    }

    private function getOtherClanRoleId()
    {
        $otherClanRoleId = \Role::select('id')
            ->where('role_code', '=', 'AB')
            ->first()
            ->toJson();
        Session::put('otherClanRoleId', $otherClanRoleId);
        return $otherClanRoleId;
    }

}