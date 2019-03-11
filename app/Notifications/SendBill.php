<?php

namespace App\Notifications;

use \User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendBill extends Notification
{
    use Queueable;

    protected $data;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * SendBill constructor.
     * @param      $data
     * @param User $user
     */
    public function __construct($data, User $user)
    {
        $this->data = $data;
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
            ->subject(env('MAIL_SUBJECT') . ' ' . trans('bill.bill_noo'))
            ->line($this->data['billusertext'])
            ->line('<hr>')
            ->line($this->data['billtext'])
            ->attach($this->data['attachment'])
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
