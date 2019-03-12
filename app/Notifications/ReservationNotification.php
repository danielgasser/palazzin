<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $data;
    protected $houseKeeper;

    /**
     * Create a new notification instance.
     *
     * @param \User $user
     * @param array $data
     * @param bool $houseKeeper
     * @return void
     */
    public function __construct(\User $user, array $data = [], bool $houseKeeper = false)
    {
        $this->user = $user;
        $this->data = $data;
        $this->houseKeeper = $houseKeeper;
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
        if ($this->houseKeeper) {
            return (new MailMessage)
                ->from(env('MAIL_USERNAME'), env('APP_NAME'))
                ->subject(env('MAIL_SUBJECT') . ' ' . trans('reservation.begin_res_housekeeper', ['z' => $this->data['total']]))
                ->line('<h1>' . trans('address.hello_official_m') . ' ' . $this->data['addressHK'] . '</h1>')
                ->line('<h4>' . $this->data['message_text'] . '</h4>')
                ->line('<p>' . $this->data['text'] . '</p>')
                ->markdown('vendor.notifications.reservation_remninder_housekeeper_email');
        }
        return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject(env('MAIL_SUBJECT') . ' ' . trans('reservation.begin_res'))
            ->line('<h4>' . $this->data['message_text'] . '</h4>')
            ->line('<p>' . trans('reservation.arrival') . ':')
            ->line('<br><b>' . $this->data['from'] . '</b></p>')
            ->line('<p>' . trans('reservation.depart') . ':')
            ->line('<br><b>' . $this->data['till'] . '</b></p>')
            ->line('<p>' . trans('reservation.guests.title') . ':')
            ->line('<ul>' . $this->data['guests'] . '</ul></p>')
            ->markdown('vendor.notifications.reservation_remninder_email', ['user' => $this->user]);
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
