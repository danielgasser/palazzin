<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileChange extends Notification
{
    use Queueable;

    protected $user;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @param \User $user
     * @param array $data
     * @return void
     */
    public function __construct(\User $user, array $data = [])
    {
        $this->user = $user;
        $this->data = $data;
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
            ->subject(env('MAIL_SUBJECT') . ' Profile change!')
            ->line('<h3>Profile Change</h3>')
   		    ->line('<p>User-ID:' . $this->data['id'] . '</p>')
            ->line('<p>Login:' . $this->data['login'] . '</p>')
            ->line('<p>Old email:' . $this->data['old_email'] . '</p>')
            ->line('<p>New email:' . $this->data['email'] . '</p>')
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
