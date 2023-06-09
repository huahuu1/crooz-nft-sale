<?php

namespace App\Notifications;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $email;

    private $token_validate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $token_validate)
    {
        $this->email = $email;
        $this->token_validate = $token_validate;
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
     * @return \App\Mail\ResetPasswordMail
     */
    public function toMail($notifiable)
    {
        return (new ResetPasswordMail($this->email, $this->token_validate))->to($this->email);
    }
}
