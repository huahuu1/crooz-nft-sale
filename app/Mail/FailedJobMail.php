<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedJobMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $email;

    public $job_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $job_name)
    {
        $this->email = $email;
        $this->job_name = $job_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.failedJob')
                    ->from(config('mail.send_failed_job'), config('mail.send_failed_job'))
                    ->subject('[AUCTION NFT JOB] The auction ranking job is failed')
                    ->with($this->email, $this->job_name);
    }
}
