<?php

namespace App\Notifications;

use App\Mail\EmailAuthenticationMail;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class EmailAuthenticationNotification extends Notification
{
    use Queueable;

    private $mail;
    private $token_validate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mail, $token_validate)
    {
        $this->mail = $mail;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
        return (new EmailAuthenticationMail($this->mail, $this->token_validate))->to($this->mail);
    }
}
