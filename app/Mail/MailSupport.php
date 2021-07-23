<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSupport extends Mailable
{
    use Queueable, SerializesModels;

    public $mailable;

    /**
     * MailSupport constructor.
     * @param $mailContent
     */
    public function __construct($mailContent)
    {
        $this->mailable = $mailContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(trans('settings/setting.school_email'))
            ->subject(trans('general.app_name') . '<br>' . trans('settings/setting.school_name'))
            ->view('help.email');

    }
}
