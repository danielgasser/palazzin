<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewPost extends Notification
{
    use Queueable;

    protected $post;
    protected $user;
    protected $postUser;

    /**
     * Create a new notification instance.
     * NewPost constructor.
     * @param \Post $post
     * @param \User $user
     */
    public function __construct(\Post $post, \User $user)
    {
        $this->post = $post;
        $this->user = $user;
        $this->postUser = \User::find($this->post->user_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject(env('MAIL_SUBJECT') . ' Neuer Beitrag')
            ->line( $this->postUser->getCompleteName() . ' hat einen neuen Beitrag gepostet.')
            ->line('<blockquote style="font-style: italic; border-left: 1px solid #cccccc; padding-left: 15px">' . $this->post->post_text . '</blockquote>')
                    ->action('Zum Beitrag', url('/news#post_' . $this->post->id))
            ->markdown('vendor.notifications.email', ['user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
