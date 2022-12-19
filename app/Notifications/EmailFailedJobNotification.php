<?php

namespace App\Notifications;

use App\Mail\FailedJobMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmailFailedJobNotification extends Notification
{
    use Queueable;

    private $email;

    private $job_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $job_name)
    {
        $this->email = $email;
        $this->job_name = $job_name;
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
        return (new FailedJobMail($this->email, $this->job_name))->to($this->email);
    }
}
