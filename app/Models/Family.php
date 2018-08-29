<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 05.02.14
 * Time: 19:48
 */
use Illuminate\Database\Eloquent\Model;

class Family extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'families';

    /**
     *
     * @var array
     */
    protected $fillable = [
        'family_code',
        'family_description'
    ];
    protected $visible = array(
        'id',
        'family_code',
        'family_description'
    );


    /**
     *
     * @return mixed
     */
    public function clans() {
        return $this->belongsTo('Clan');
    }

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->hasMany('User');
    }


}