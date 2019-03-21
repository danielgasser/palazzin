<?php
/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 01.11.14
 * Time: 19:14
 */

class Constants {

    /**
     * Max length of an IP-Address
     *
     */
    const ipMaxLength = 45;

    /**
     * Max login attempts before blocking user
     *
     */
    const maxLogins = 3;

    /**
     * Webmaster infos
     *
     */
    const webMaster = 'Administrator';
    const webMasterName = 'Daniel Gasser';
    const webMasterMail = 'software@toesslab.ch';

    /**
     * @var array fon-labels
     *
     */
    private static $fonLabels = array(
        'x' => '-',
        'mobile' => 'Mobile',
        'home' => 'Private',
        'business' => 'Business'
    );

    /**
     * Translates $fonLabels
     *
     * @return array
     */
    public static function translateFonLabels () {
        $arr = array();
        foreach (self::$fonLabels as $k => $v) {
            $arr[$k] = trans('userdata.fonlabel.' . $k);
        }
        return $arr;
    }
}
