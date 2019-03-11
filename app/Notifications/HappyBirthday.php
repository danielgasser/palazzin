<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HappyBirthday extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * BirthdayMessage constructor.
     * @param \User $user
     */
    public function __construct(\User $user)
    {
        $this->user = $user;
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
            ->subject(env('MAIL_SUBJECT') . ' Happy Birthday')
            ->line('<h1 style="text-align: center">' . $this->user->user_first_name . ', alles Gute zum Geburtstag!</h1>')
            ->line('<div><img src="' . asset('img/happy_birthday.jpg') . '"></div>')
            ->markdown('vendor.notifications.birthday_email', ['user' => $this->user]);
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
