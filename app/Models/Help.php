<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;

class Help extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'helps';

    /**
     *
     * @var array
     */
    protected $fillable = array(
       'help_text',
       'help_title',
       'help_topic',
        'help_lang'
    );

    /**
     * @param $h
     * @return mixed
     */
    public function getHelp ($h) {
        $help = $this->where('help_topic', '=', $h)->select('help_topic', 'help_text as help')->first();
        if(is_object($help)) {
            $help->help_text = utf8_encode($help->help);
            return $help;
        }
        return null;
    }

}
