<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    protected $token;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * ResetPassword constructor.
     * @param $token
     * @param \User $user
     */
    public function __construct($token, \User $user)
    {
        $this->token = $token;
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
            ->subject(env('MAIL_SUBJECT') . ' Passwort zurücksetzen')
            ->line('Du bekommst diese E-Mail, weil palazzin.ch eine Anfrage zum Zurücksetzen Deines Passwortes erhalten hat.')
            ->action('Passwort jetzt zurücksetzen', url(config('app.url').route('password.reset', [$this->token, 'email' => $this->user->email], false)))
            ->line('Falls Du keine solche Anfrage gemacht hast, musst Du nichts machen und kannst diese E-Mail einfach ignorieren.')
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
