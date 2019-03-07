<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 05.02.14
 * Time: 19:48
 */
use Illuminate\Database\Eloquent\Model;

class LocalStorage extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'local_storage';
    /**
     *
     * @var array
     */
    protected $fillable = [
        'local_storage_date',
        'local_storage_number'
    ];

    public function saveLocalStorage($args)
    {
        foreach($args['key'] as $k => $a) {
            $localStorage = self::firstOrNew([
                'local_storage_date' => $a['local_storage_date']
            ]);
            $localStorage->local_storage_number = $a['local_storage_number'];
            $localStorage->save();
        }
    }
}
