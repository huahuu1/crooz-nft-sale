<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailAuthenticationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $email;

    public $token_validate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token_validate)
    {
        $this->email = $email;
        $this->token_validate = $token_validate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.emailAuthentication')
                    ->from(config('mail.send_token_validate'), config('mail.send_token_validate'))
                    ->subject('[Xeno] Verify your email')
                    ->with('mail', $this->email, $this->token_validate);
    }
}
