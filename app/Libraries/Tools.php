<?php
/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 05.10.14
 * Time: 04:23
 */

class Tools {

    /**
     * Improved Laravel's dd()
     *
     * @param string $var
     * @param bool $exit
     * @param string $title
     */
    public static function dd ($var = '', $exit = true, $title = ''){
        echo '<pre>' . $title . '<br>';
        print_r($var);
        echo '</pre>';
        if ($exit) exit;
    }
}
