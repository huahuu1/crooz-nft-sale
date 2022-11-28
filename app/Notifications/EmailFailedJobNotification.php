<?php

namespace App\Notifications;

use App\Mail\FailedJobMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmailFailedJobNotification extends Notification
{
    use Queueable;

    private $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
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
     * @return \App\Mail\FailedJobMail
     */
    public function toMail($notifiable)
    {
        return (new FailedJobMail($this->email))->to($this->email);
    }
}
