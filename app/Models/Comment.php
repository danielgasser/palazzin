<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'comment_text',
        'user_id'
    );

    /**
     *
     * @return mixed
     */
    public function posts() {
        return $this->belongsTo('Post');
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
     * Gets comments by Post->id
     *
     * @param $id Post->id
     * @param int $take Limit
     * @return mixed Collection
     */
    public function getCommentsByPostId ($id, $take = 3) {
        $comments = $this->join('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', '=', $id)
            ->select('users.id as user_id', 'users.user_login_name', 'users.email', 'comments.id', 'comments.comment_text', 'comments.updated_at', 'comments.created_at')
            ->orderBy('comments.created_at', 'DESC')
            ->get();
        $comments->each(function ($c) {
            $c->editable = Post::checkEditableRecord($c->created_at);
        });
        return $comments;
    }

    /**
     * Fills a Comment & save/updates it
     *
     * @param $ct
     * @param $pid
     * @return $this
     */
    public function addComment ($ct, $pid) {
        $set = Setting::getStaticSettings();
        $this->user_id = Auth::id();
        $this->comment_text = $ct;
        $this->post_id = $pid;
        $this->save();
        $postUser = \User::find(Post::find($pid)->user_id);
        $this->email = $postUser->email;
        $this->user_login_name = $postUser->user_login_name;
        $this->editable = Post::checkEditableRecord($this->created_at);
        $data = array(
            'new_comment' => $this->id,
            'new_comment_user_id' => $this->user_id
        );
        Mail::send('emails.new_comment', $data, function($message) use($postUser, $set)
        {
            $message->to($postUser->email, $postUser->user_login_name)
                //->cc('info@pc-shooter.ch', 'Daniel Gasser')
                ->from($set->setting_app_owner_email, $set->setting_app_owner)
                ->sender($set->setting_app_owner_email, $set->setting_app_owner)
                ->subject(trans('comments.new_comment_available'));
        });

        return $this;
    }

}
