<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 05.02.14
 * Time: 19:48
 */
use Illuminate\Database\Eloquent\Model;

class Generation extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'clans';
    /**
     *
     * @var array
     */
    protected $fillable = [
        'clan_code',
        'clan_description'
    ];

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->hasMany('User', 'clan_id');
    }
}
