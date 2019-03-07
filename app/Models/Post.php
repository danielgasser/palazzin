<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     *
     * @var array
     */
    protected $fillable = array(
       'post_text',
       'user_id'
    );

    /**
     *
     * @return mixed
     */
    public function users() {
        return $this->belongsTo('User');
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.language-name-ucfirst'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.short-date-time'));
    }

    /**
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        setlocale(LC_ALL, trans('formats.langlang'));
        return $carbonDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->formatLocalized(trans('formats.short-date-time'));
    }

    /**
     * Get all posts or by id
     *
     * @param null $id
     * @param false $isJson
     * @return Post
     */
    public function getNewsTicker ($id = null, $isJson = false) {

        $posts = new Post();
        if ($id == null) {
            $posts = $posts->join('users', 'user_id', '=', 'users.id')
                ->select('posts.id', 'posts.created_at', 'posts.updated_at', 'post_text', 'users.id as uid', 'posts.user_id', 'users.user_login_name', 'users.email')
                ->orderBy('posts.created_at', 'DESC')
                ->get();
        } else {
            $posts = $posts->join('users', 'user_id', '=', 'users.id')
                ->where('posts.id', '=', $id)
                ->select('posts.id', 'posts.created_at', 'posts.updated_at', 'post_text', 'users.id as uid', 'posts.user_id', 'users.user_login_name', 'users.email')
                ->orderBy('posts.created_at', 'DESC')
                ->get();
        }

        $posts->each(function ($p) {
            $p->editable = self::checkEditableRecord($p->created_at);
        });
        if ($isJson) {
            return json_encode($posts);
        }
        return $posts;
    }
    /**
     * Checks if a record is editable
     *
     * @param $createdAt date string
     * @return bool
     */
    public static function checkEditableRecord ($createdAt) {
        $set = Setting::getStaticSettings();

        date_default_timezone_set(date_default_timezone_get());
        $editableDate = new \DateTime();
        $created_at = new \DateTime(str_replace('.', '-', $createdAt));
        $interval = $created_at->diff($editableDate);
        $minutes = $interval->days * 24 * 60;
        $minutes += $interval->h * 60;
        $minutes += $interval->i;
        //Tools::dd($minutes, false);
        //Tools::dd($set->setting_editable_record_time, false);
        if ($minutes < $set->setting_editable_record_time) {
            return 1;
        }
        return 0;

    }

}
