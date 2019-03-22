<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MovingNotification extends Notification
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
            ->subject(env('MAIL_SUBJECT') . ' Bist Du umgezogen?')
            ->line('Hat sich Deine Adresse geändert?')
            ->line('Falls nicht, brauchst Du gar nichts zu unternehmen.')
            ->line('Sonst bitten wir Dich, Dir einen Moment Zeit zu nehmen, um Deine Adresse zu aktualisieren.')
            ->action('Adresse jetzt anpassen', url(config('app.url').'/login_user/' . $this->user->id))
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
