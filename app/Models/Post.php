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
                ->select('posts.id', 'posts.created_at', 'posts.updated_at', 'post_text', 'users.id as uid', 'posts.user_id', 'users.user_first_name', 'users.user_name', 'users.email')
                ->orderBy('posts.created_at', 'DESC')
                ->get();
        } else {
            $posts = $posts->join('users', 'user_id', '=', 'users.id')
                ->where('posts.id', '=', $id)
                ->select('posts.id', 'posts.created_at', 'posts.updated_at', 'post_text', 'users.id as uid', 'posts.user_id', 'users.user_first_name', 'users.user_name', 'users.email')
                ->orderBy('posts.created_at', 'DESC')
                ->get();
        }
        if ($isJson) {
            return json_encode($posts);
        }
        return $posts;
    }
}
