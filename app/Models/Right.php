<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 25.01.14
 * Time: 14:22
 */
use Illuminate\Database\Eloquent\Model;

class Right extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rights';

    /**
     *
     * @return mixed
     */
    public function roles(){
        return $this->belongsToMany('Role')->withTimestamps();
    }
}