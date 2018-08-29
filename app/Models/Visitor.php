<?php
/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 01.11.14
 * Time: 18:38
 */
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'visitors';

    /**
     *
     * @var array
     */
    protected $fillable = array('visitor_ip', 'visitor_false_logins');

    /**
     * Sets the maximum allowed failed login attempts
     *
     * @return bool
     */
    public function setMaxFalseLogins () {
        $e = $this->firstOrCreate(array('visitor_ip' => Request::getClientIp()));
        $e->visitor_false_logins++;
        if($e->visitor_false_logins > Constants::maxLogins) return true;
        $e->save();
        return false;
    }

    /**
     * Deletes the visitor_false_logins record
     *
     * @return bool
     */
    public function deleteOnSuccess () {
        self::where('visitor_ip', '=', Request::getClientIp())->delete();
        return true;
    }
}